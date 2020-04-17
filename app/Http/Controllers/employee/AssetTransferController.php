<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\AssetTransferRequest;
use App\Http\Requests\Employee\AssetTransferReturnRequest;
use App\Models\Employee\AssetTransfer;
use App\Repositories\Employee\AssetTransferRepository;
use App\Repositories\Upload\UploadRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssetTransferController extends Controller
{
    protected $request;
    protected $repo;
    protected $upload;
    protected $module = 'asset_transfer';

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(
        Request $request,
        AssetTransferRepository $repo,
        UploadRepository $upload
    ) {
        $this->request = $request;
        $this->repo = $repo;
        $this->upload = $upload;
    }

    /**
     * Used to get pre requisites
     * @get ("/api/asset/transfer/pre-requisite")
     * @return Response
     */
    public function preRequisite()
    {
        // $this->authorize('preRequisite', AssetTransfer::class);

        return $this->success($this->repo->getPreRequisite());
    }

    /**
     * Used to get all Asset Transfers
     * @get ("/api/asset/transfer")
     * @return Response
     */
    public function index()
    {
        // $this->authorize('list', AssetTransfer::class);

        $asset_transfers = $this->repo->paginate($this->request->all());

        $filters = $this->repo->getFilters();

        return $this->success(compact('asset_transfers', 'filters'));
    }

    /**
     * Used to print all Asset Transfers
     * @post ("/api/asset/transfer/print")
     * @return Response
     */
    public function print()
    {
        $asset_transfers = $this->repo->print(request('filter'));

        return view('print.employee_asset.asset-transfer', compact('asset_transfers'))->render();
    }

    /**
     * Used to print Asset Transfer details
     * @post ("/api/asset/transfer/{id}/print")
     * @return Response
     */
    public function printDetail($id)
    {
        $asset_transfer = $this->repo->findOrFail($id);
        $asset_transfer['sub_total'] = array_reduce(array_map(function($detail) {
            return $detail['price'] * $detail['quantity'];
        }, $asset_transfer->details->toArray()), function($carry, $item) {
            $carry += $item;
            return $carry;
        });
        $asset_transfer['total'] = 0;
        $asset_transfer['total'] += $asset_transfer['sub_total'];
        $asset_transfer['total'] -= $asset_transfer->discount;
        $asset_transfer['paid'] = ($asset_transfer->payment_method === 'wallet') ? $asset_transfer['total'] : $asset_transfer['cash_paid'];
        $asset_transfer['balance'] = $asset_transfer['total'] - $asset_transfer['paid'];

        return view('print.employee_asset.asset-transfer-detail', compact('asset_transfer'))->render();
    }

    /**
     * Used to generate pdf all Asset Transfers
     * @post ("/api/asset/transfer/pdf")
     * @return Response
     */
    public function pdf()
    {
        $asset_transfers = $this->repo->print(request('filter'));

        $uuid = Str::uuid();
        $pdf = \PDF::loadView('print.employee_asset.asset-transfer', compact('asset_transfers'))->save('../storage/app/downloads/'.$uuid.'.pdf');

        return $uuid;
    }

    /**
     * Used to store Asset Transfer
     * @post ("/api/asset/transfer")
     * @param ({
     *      @Parameter("vendor_id", type="integer", required="required", description="Id of Vendor"),
     *      @Parameter("date", type="date", required="required", description="Date of Transfer"),
     * })
     * @return Response
     */
    public function store(AssetTransferRequest $request)
    {
        // $this->authorize('create', AssetTransfer::class);

        $asset_transfer = $this->repo->create($this->request->all());

        return $this->success(['message' => trans('employee_asset.asset_transfer_added')]);
    }

    /**
     * Used to get Asset Transfer detail
     * @get ("/api/asset/transfer/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Asset Transfer"),
     * })
     * @return Response
     */
    public function show($id)
    {
        // $this->authorize('list', AssetTransfer::class);

        $asset_transfer = $this->repo->findOrFail($id);

        $attachments = $this->upload->getAttachment($this->module, $asset_transfer->id);

        $selected_employee = ($asset_transfer->employee_id) ? ['id' => $asset_transfer->employee_id, 'name' => $asset_transfer->Employee->name.' ('.$asset_transfer->Employee->contact_number.')'] : [];

        return $this->success(compact('asset_transfer', 'attachments','selected_employee'));
    }

    /**
     * Used to update Asset Transfer
     * @patch ("/api/asset/transfer/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Asset Transfer"),
     *      @Parameter("vendor_id", type="integer", required="required", description="Id of Vendor"),
     *      @Parameter("date", type="date", required="required", description="Date of Transfer"),
     * })
     * @return Response
     */
    public function update($id, AssetTransferRequest $request)
    {
        // $this->authorize('update', AssetTransfer::class);

        $asset_transfer = $this->repo->findOrFail($id);

        $asset_transfer = $this->repo->update($asset_transfer, $this->request->all());

        return $this->success(['message' => trans('employee_asset.asset_transfer_updated')]);
    }

    /**
     * Used to delete Asset Transfer
     * @delete ("/api/asset/transfer/{id}")
     * @param ({
     *      @Parameter("id", type="string", required="true", description="Unique Id of Asset Transfer"),
     * })
     * @return Response
     */
    public function destroy($id)
    {
        // $this->authorize('delete', AssetTransfer::class);

        $asset_transfer = $this->repo->findOrFail($id);

        $this->upload->delete($this->module, $asset_transfer->id);

        $this->repo->delete($asset_transfer);

        return $this->success(['message' => trans('employee_asset.asset_transfer_deleted')]);
    }

    /**
     * Used to return Asset Transfer Item
     * @post ("/api/asset/transfer/{id}/return")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Asset Transfer")
     * })
     * @return Response
     */
    public function returnItem($id, AssetTransferReturnRequest $request)
    {
        // $this->authorize('update', AssetTransfer::class);

        $asset_transfer = $this->repo->findOrFail($id);

        $asset_transfer = $this->repo->returnItem($asset_transfer, $this->request->all());

        return $this->success(['message' => trans('employee_asset.asset_transfer_updated')]);
    }

    /**
     * Used to delete Asset Transfer Return
     * @delete ("/api/asset/transfer/{id}/return/{return_id}")
     * @param ({
     *      @Parameter("id", type="string", required="true", description="Unique Id of Asset Transfer"),
     * })
     * @return Response
     */
    public function destroyReturn($id, $return_id)
    {
        // $this->authorize('delete', AssetTransfer::class);

        $asset_transfer = $this->repo->findOrFail($id);

        $this->repo->deleteReturn($asset_transfer, $return_id);

        return $this->success(['message' => trans('employee_asset.asset_transfer_return_deleted')]);
    }

    /**
     * Used to download Asset Transfer Attachments
     * @get ("/asset/transfer/{id}/attachment/{attachment_uuid}/download")
     * @param ({
     *      @Parameter("uuid", type="string", required="true", description="Unique Id of Asset Transfer"),
     *      @Parameter("attachment_uuid", type="string", required="true", description="Unique Id of Attachment"),
     * })
     * @return Response download
     */
    public function download($id, $attachment_uuid)
    {
        // $this->authorize('list', AssetTransfer::class);
        
        $asset_transfer = $this->repo->findOrFail($id);

        $attachment = $this->upload->getAttachment($this->module, $asset_transfer->id, $attachment_uuid);

        if (! \Storage::exists($attachment->filename)) {
            return view('errors.file-not-found');
        }

        $download_path = storage_path('app/'.$attachment->filename);
        return response()->download($download_path, $attachment->user_filename);
    }
}