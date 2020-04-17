<?php
namespace App\Repositories\Employee;

use App\Models\Employee\AssetTransfer;
use App\Models\Employee\AssetTransferDetail;
use App\Models\Employee\AssetTransferReturn;
use App\Repositories\Configuration\Employee\AssetItemRepository;
use App\Repositories\Employee\EmployeeRepository;
use App\Repositories\Upload\UploadRepository;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AssetTransferRepository
{
    protected $asset_transfer;
    protected $asset_transfer_detail;
    protected $asset_item;
    protected $upload;
    protected $employee;
    protected $asset_transfer_return;
    protected $module = 'asset_transfer';

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(
        AssetTransfer $asset_transfer,
        AssetTransferDetail $asset_transfer_detail,
        AssetItemRepository $asset_item,
        UploadRepository $upload,
        EmployeeRepository $employee,
        AssetTransferReturn $asset_transfer_return
    ) {
        $this->asset_transfer = $asset_transfer;
        $this->asset_transfer_detail = $asset_transfer_detail;
        $this->asset_item = $asset_item;
        $this->upload = $upload;
        $this->employee = $employee;
        $this->asset_transfer_return = $asset_transfer_return;
    }

    /**
     * Find asset transfer with given id.
     *
     * @param integer $id
     * @return AssetTransfer
     */
    public function find($id)
    {
        return $this->asset_transfer->info()->filterBySession()->filterById($id)->first();
    }

    /**
     * Find asset transfer with given id or throw an error.
     *
     * @param integer $id
     * @return AssetTransfer
     */
    public function findOrFail($id, $field = 'message')
    {
        $asset_transfer = $this->asset_transfer->info()->filterBySession()->filterById($id)->first();

        if (! $asset_transfer) {
            throw ValidationException::withMessages([$field => trans('employee_asset.could_not_find_asset_transfer')]);
        }

        return $asset_transfer;
    }

    /**
     * Get all filtered data
     *
     * @param array $params
     * @return AssetTransfer
     */
    public function getData($params)
    {
        $sort_by = gv($params, 'sort_by', 'date');
        $order   = gv($params, 'order', 'desc');

        $date_start_date = gv($params, 'date_start_date');
        $date_end_date   = gv($params, 'date_end_date');

        $query = $this->asset_transfer->info()->filterBySession()->dateBetween([
            'start_date' => $date_start_date,
            'end_date' => $date_end_date
        ]);

        return $query->orderBy($sort_by, $order);
    }

    /**
     * Paginate all asset transfers using given params.
     *
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($params)
    {
        $page_length = gv($params, 'page_length', config('config.page_length'));

        return $this->getData($params)->paginate($page_length);
    }

    /**
     * Get all filtered data for printing
     *
     * @param array $params
     * @return AssetTransfer
     */
    public function print($params)
    {
        return $this->getData($params)->get();
    }

    /**
     * Get asset transfer filters.
     *
     * @return Array
     */
    public function getFilters()
    {
        return $this->getPreRequisite();
    }

    /**
     * Get pre requisite
     *
     * @return Array
     */
    public function getPreRequisite()
    {
        $asset_items = $this->asset_item->selectAll();
        $employees = $this->employee->selectAll();

        return compact('asset_items','employees');
    }

    /**
     * Create a new asset transfer.
     *
     * @param array $params
     * @return AssetTransfer
     */
    public function create($params)
    {
        $this->validateInput($params);

        beginTransaction();

        $type = gv($params, 'type');

        $asset_transfer = $this->asset_transfer->forceCreate([
            'type'            => $type,
            'employee_id'     => $type == 'employee' ? gv($params, 'employee_id') : null,
            'date'            => toDate(gv($params, 'date')),
            'return_due_date' => toDate(gv($params, 'return_due_date')),
            'description'     => gv($params, 'description'),
            'user_id'         => \Auth::user()->id,
            'upload_token'    => gv($params, 'upload_token') ? : Str::uuid(),
            'options'         => []
        ]);

        foreach (gv($params, 'details') as $detail) {
            $asset_transfer_detail = $this->asset_transfer_detail->forceCreate([
                'asset_transfer_id' => $asset_transfer->id,
                'quantity'      => gv($detail, 'quantity', 0),
                'asset_item_id' => gv($detail, 'asset_item_id'),
                'description'   => gv($detail, 'description'),
                'options'       => []
            ]);

            $asset_item = $asset_transfer_detail->item;
        }
        
        $this->processUpload($asset_transfer, $params);

        commitTransaction();

        return $asset_transfer;
    }

    /**
     * Upload attachment
     *
     * @param AssetTransfer $asset_transfer
     * @param array $params
     * @param string $action
     * @return void
     */
    public function processUpload(AssetTransfer $asset_transfer, $params = array(), $action = 'create')
    {
        $upload_token = gv($params, 'upload_token');

        if ($action === 'create') {
            $this->upload->store($this->module, $asset_transfer->id, $upload_token);
        } else {
            $this->upload->update($this->module, $asset_transfer->id, $upload_token);
        }
    }

    /**
     * Validate asset transfer input.
     *
     * @param array $params
     * @return void
     */
    public function validateInput($params, $asset_transfer_id = null)
    {
        if (! dateBetweenSession(gv($params, 'date'))) {
            throw ValidationException::withMessages(['date' => trans('academic.invalid_session_date_range')]);
        }

        $type = gv($params, 'type');
        $employee_id = gv($params, 'employee_id');

        if ($type == 'employee') {
            $employee = $this->employee->findorFail($employee_id);
        } 

        $details = gv($params, 'details');

        if (! $details) {
            throw ValidationException::withMessages(['message' => trans('employee_asset.no_item_found')]);
        }

        $asset_item_ids    = $this->asset_item->listId();

        $item_ids = array();
        foreach ($details as $index => $detail) {
            $asset_item_id = gv($detail, 'asset_item_id');
            $quantity      = gv($detail, 'quantity', 0);

            if (! in_array($asset_item_id, $asset_item_ids)) {
                throw ValidationException::withMessages([$index.'_asset_item_id' => trans('employee_asset.could_not_find_asset_item')]);
            }

            $item_ids[] = $asset_item_id;
        }

        if (count($item_ids) > count(array_unique($item_ids))) {
            throw ValidationException::withMessages(['message' => trans('employee_asset.duplicate_item_found')]);
        }
    }

    /**
     * Update given asset transfer.
     *
     * @param AssetTransfer $asset_transfer
     * @param array $params
     *
     * @return AssetTransfer
     */
    public function update(AssetTransfer $asset_transfer, $params)
    {
        $this->validateInput($params, $asset_transfer->id);

        beginTransaction();

        $type = gv($params, 'type');

        $asset_transfer->type            = $type;
        $asset_transfer->employee_id     = $type == 'employee' ? gv($params, 'employee_id') : null;
        $asset_transfer->date            = toDate(gv($params, 'date'));
        $asset_transfer->return_due_date = toDate(gv($params, 'return_due_date'));
        $asset_transfer->description     = gv($params, 'description');
        $asset_transfer->save();

        $asset_item_id = array();
        foreach ($params['details'] as $detail) {
            $asset_item_id[] = gv($detail, 'asset_item_id');
        }

        $existing_asset_item_ids = $asset_transfer->details->pluck('asset_item_id')->all();
        foreach ($existing_asset_item_ids as $existing_asset_item_id) {
            if (! in_array($existing_asset_item_id, $asset_item_id)) {
                $asset_transfer_detail = $this->asset_transfer_detail->whereAssetItemId($existing_asset_item_id)->first();
                $asset_item = $asset_transfer_detail->item;
                $asset_transfer_detail->delete();
            }
        }

        foreach ($params['details'] as $detail) {
            $asset_transfer_detail = $this->asset_transfer_detail->firstOrCreate([
                'asset_transfer_id' => $asset_transfer->id,
                'asset_item_id' => gv($detail, 'asset_item_id')
            ]);

            $old_quantity = $asset_transfer_detail->quantity ? : 0;

            $asset_transfer_detail->quantity    = gv($detail, 'quantity', 0);
            $asset_transfer_detail->description = gv($detail, 'description');
            $asset_transfer_detail->options     = [];
            $asset_transfer_detail->save();

        }

        $this->processUpload($asset_transfer, $params, 'update');

        commitTransaction();
    }

    /**
     * Return item
     * @param  AssetTransfer $asset_transfer
     * @param  array $params        
     * @return void
     */
    public function returnItem(AssetTransfer $asset_transfer, $params = array())
    {
        $date = gv($params, 'date');
        $quantity = gv($params, 'quantity');
        $description = gv($params, 'description');
        $type = gv($params, 'type');

        if ($date < toDate($asset_transfer->date)) {
            throw ValidationException::withMessages(['date' => trans('employee_asset.return_date_should_greater_than_equal_to_asset_transfer_date')]);
        }

        $asset_item_id = gv($params, 'asset_item_id');

        $asset_transfer_detail = $asset_transfer->details->where('asset_item_id', $asset_item_id)->first();

        if (! $asset_transfer_detail) {
            throw ValidationException::withMessages(['message' => trans('employee_asset.could_not_find_asset_item')]);
        }

        $asset_item = $asset_transfer_detail->item;

        $item_return_count = $this->asset_transfer_return->whereAssetTransferId($asset_transfer->id)->whereAssetItemId($asset_item_id)->sum('quantity');

        if ($asset_transfer_detail->quantity < ($quantity + $item_return_count)) {
            throw ValidationException::withMessages(['message' => trans('employee_asset.asset_transfer_return_quantity_exceeded')]);
        }

        $this->asset_transfer_return->forceCreate([
            'asset_transfer_id' => $asset_transfer->id,
            'asset_item_id' => $asset_item->id,
            'type' => $type,
            'quantity' => $quantity,
            'description' => $description,
            'date' => toDate($date),
        ]);
    }

    /**
     * Delete asset transfer return.
     *
     * @param AssetTransfer $asset_transfer
     * @param integer $id
     * @return bool|null
     */
    public function deleteReturn(AssetTransfer $asset_transfer, $id)
    {
        beginTransaction();

        $asset_transfer_return = $this->asset_transfer_return->whereAssetTransferId($asset_transfer->id)->whereId($id)->first();

        if (! $asset_transfer_return) {
            throw ValidationException::withMessages(['message' => trans('employee_asset.could_not_find_asset_transfer_return')]);
        }

        $asset_transfer_return->delete();

        commitTransaction();
    }

    /**
     * Delete asset transfer.
     *
     * @param integer $id
     * @return bool|null
     */
    public function delete(AssetTransfer $asset_transfer)
    {
        beginTransaction();

        $asset_transfer->delete();

        commitTransaction();
    }

    /**
     * Delete multiple asset transfer.
     *
     * @param array $ids
     * @return bool|null
     */
    public function deleteMultiple($ids)
    {
        return $this->asset_transfer->whereIn('id', $ids)->delete();
    }
}