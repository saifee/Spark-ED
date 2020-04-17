<?php

namespace App\Http\Controllers\Configuration\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Employee\AssetItemRequest;
use App\Models\Configuration\Employee\AssetItem;
use App\Repositories\Configuration\Employee\AssetItemRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssetItemController extends Controller
{
    protected $request;
    protected $repo;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(
        Request $request,
        AssetItemRepository $repo
    ) {
        $this->request = $request;
        $this->repo = $repo;
    }

    /**
     * Used to get pre requisites
     * @get ("/api/asset/item/pre-requisite")
     * @return Response
     */
    public function preRequisite()
    {
        return $this->ok($this->repo->getPreRequisite());
    }

    /**
     * Used to get all Employee Asset Items
     * @get ("/api/asset/item")
     * @return Response
     */
    public function index()
    {
        // $this->authorize('list', AssetItem::class);

        $asset_items = $this->repo->paginate($this->request->all());

        $filters = $this->repo->getFilters();

        return $this->success(compact('asset_items', 'filters'));
    }

    /**
     * Used to print all Employee Asset Items
     * @post ("/api/asset/item/print")
     * @return Response
     */
    public function print()
    {
        $asset_items = $this->repo->print(request('filter'));

        return view('print.employee_asset.asset-item', compact('asset_items'))->render();
    }

    /**
     * Used to generate pdf all Employee Asset Items
     * @post ("/api/asset/item/pdf")
     * @return Response
     */
    public function pdf()
    {
        $asset_items = $this->repo->print(request('filter'));

        $uuid = Str::uuid();
        $pdf = \PDF::loadView('print.employee_asset.asset-item', compact('asset_items'))->save('../storage/app/downloads/'.$uuid.'.pdf');

        return $uuid;
    }

    /**
     * Used to store Employee Asset Item
     * @post ("/api/asset/item")
     * @param ({
     *      @Parameter("name", type="string", required="true", description="Name of Asset Item"),
     *      @Parameter("description", type="text", required="true", description="Description of Asset Item"),
     * })
     * @return Response
     */
    public function store(AssetItemRequest $request)
    {
        // $this->authorize('create', AssetItem::class);

        $asset_item = $this->repo->create($this->request->all());

        return $this->success(['message' => trans('employee_asset.asset_item_added')]);
    }

    /**
     * Used to get Employee Asset Item detail
     * @get ("/api/asset/item/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Employee Asset Item"),
     * })
     * @return Response
     */
    public function show($id)
    {
        // $this->authorize('list', AssetItem::class);

        $asset_item = $this->repo->findOrFail($id);

        $selected_asset_category = ['id' => $asset_item->asset_category_id, 'name' => $asset_item->category->name];

        return $this->success(compact('asset_item','selected_asset_category'));
    }

    /**
     * Used to update Employee Asset Item
     * @patch ("/api/asset/item/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Employee Asset Item"),
     *      @Parameter("name", type="string", required="true", description="Name of Asset Item"),
     *      @Parameter("description", type="text", required="true", description="Description of Asset Item"),
     * })
     * @return Response
     */
    public function update($id, AssetItemRequest $request)
    {
        // $this->authorize('update', AssetItem::class);

        $asset_item = $this->repo->findOrFail($id);

        $asset_item = $this->repo->update($asset_item, $this->request->all());

        return $this->success(['message' => trans('employee_asset.asset_item_updated')]);
    }

    /**
     * Used to delete Employee Asset Item
     * @delete ("/api/asset/item/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Employee Asset Item"),
     * })
     * @return Response
     */
    public function destroy($id)
    {
        // $this->authorize('delete', AssetItem::class);

        $asset_item = $this->repo->deletable($id);

        $this->repo->delete($asset_item);

        return $this->success(['message' => trans('employee_asset.asset_item_deleted')]);
    }
}