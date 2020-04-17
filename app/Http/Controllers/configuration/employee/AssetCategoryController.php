<?php

namespace App\Http\Controllers\Configuration\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Employee\AssetCategoryRequest;
use App\Models\Configuration\Employee\AssetCategory;
use App\Repositories\Configuration\Employee\AssetCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssetCategoryController extends Controller
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
        AssetCategoryRepository $repo
    ) {
        $this->request = $request;
        $this->repo = $repo;
    }

    /**
     * Used to get all Employee Asset Categories
     * @get ("/api/asset/category")
     * @return Response
     */
    public function index()
    {
        // $this->authorize('list', AssetCategory::class);

        return $this->ok($this->repo->paginate($this->request->all()));
    }

    /**
     * Used to print all Employee Asset Categories
     * @post ("/api/asset/category/print")
     * @return Response
     */
    public function print()
    {
        $asset_categories = $this->repo->print(request('filter'));

        return view('print.employee_asset.asset-category', compact('asset_categories'))->render();
    }

    /**
     * Used to generate pdf all Employee Asset Categories
     * @post ("/api/asset/category/pdf")
     * @return Response
     */
    public function pdf()
    {
        $asset_categories = $this->repo->print(request('filter'));

        $uuid = Str::uuid();
        $pdf = \PDF::loadView('print.employee_asset.asset-category', compact('asset_categories'))->save('../storage/app/downloads/'.$uuid.'.pdf');

        return $uuid;
    }

    /**
     * Used to store Employee Asset Category
     * @post ("/api/asset/category")
     * @param ({
     *      @Parameter("name", type="string", required="true", description="Name of Asset Category"),
     *      @Parameter("description", type="text", required="true", description="Description of Asset Category"),
     * })
     * @return Response
     */
    public function store(AssetCategoryRequest $request)
    {
        // $this->authorize('create', AssetCategory::class);

        $asset_category = $this->repo->create($this->request->all());

        return $this->success(['message' => trans('employee_asset.asset_category_added')]);
    }

    /**
     * Used to get Employee Asset Category detail
     * @get ("/api/asset/category/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Employee Asset Category"),
     * })
     * @return Response
     */
    public function show($id)
    {
        // $this->authorize('list', AssetCategory::class);

        return $this->ok($this->repo->findOrFail($id));
    }

    /**
     * Used to update Employee Asset Category
     * @patch ("/api/asset/category/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Employee Asset Category"),
     *      @Parameter("name", type="string", required="true", description="Name of Asset Category"),
     *      @Parameter("description", type="text", required="true", description="Description of Asset Category"),
     * })
     * @return Response
     */
    public function update($id, AssetCategoryRequest $request)
    {
        // $this->authorize('update', AssetCategory::class);

        $asset_category = $this->repo->findOrFail($id);

        $asset_category = $this->repo->update($asset_category, $this->request->all());

        return $this->success(['message' => trans('employee_asset.asset_category_updated')]);
    }

    /**
     * Used to delete Employee Asset Category
     * @delete ("/api/asset/category/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Employee Asset Category"),
     * })
     * @return Response
     */
    public function destroy($id)
    {
        // $this->authorize('delete', AssetCategory::class);

        $asset_category = $this->repo->deletable($id);

        $this->repo->delete($asset_category);

        return $this->success(['message' => trans('employee_asset.asset_category_deleted')]);
    }
}