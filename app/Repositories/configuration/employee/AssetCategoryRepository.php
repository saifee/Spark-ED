<?php
namespace App\Repositories\Configuration\Employee;

use App\Models\Configuration\Employee\AssetCategory;
use Illuminate\Validation\ValidationException;

class AssetCategoryRepository
{
    protected $asset_category;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(
        AssetCategory $asset_category
    ) {
        $this->asset_category = $asset_category;
    }

    /**
     * Get asset category query
     *
     * @return AssetCategory query
     */
    public function getQuery()
    {
        return $this->asset_category;
    }

    /**
     * Count asset category
     *
     * @return integer
     */
    public function count()
    {
        return $this->asset_category->count();
    }

    /**
     * Get all asset categories
     *
     * @return array
     */
    public function getAll()
    {
        return $this->asset_category->all();
    }

    /**
     * List all asset categories by name & id for select option
     *
     * @return array
     */

    public function selectAll()
    {
        return $this->asset_category->get(['name', 'id']);
    }

    /**
     * Find asset category with given id.
     *
     * @param integer $id
     * @return AssetCategory
     */
    public function find($id)
    {
        return $this->asset_category->info()->find($id);
    }

    /**
     * Find asset category with given id or throw an error.
     *
     * @param integer $id
     * @return AssetCategory
     */
    public function findOrFail($id, $field = 'message')
    {
        $asset_category = $this->asset_category->info()->find($id);

        if (! $asset_category) {
            throw ValidationException::withMessages([$field => trans('employee_asset.could_not_find_asset_category')]);
        }

        return $asset_category;
    }

    /**
     * Get all filtered data
     *
     * @param array $params
     * @return AssetCategory
     */
    public function getData($params)
    {
        $sort_by = gv($params, 'sort_by', 'name');
        $order   = gv($params, 'order', 'desc');
        $name    = gv($params, 'name');

        return $this->asset_category->info()->filterByName($name)->orderBy($sort_by, $order);
    }

    /**
     * Paginate all asset categories using given params.
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
     * @return AssetCategory
     */
    public function print($params)
    {
        return $this->getData($params)->get();
    }

    /**
     * Get asset category pre requisite.
     *
     * @return Array
     */
    public function getPreRequisite()
    {
        return [];
    }

    /**
     * Get asset category filters.
     *
     * @return Array
     */
    public function getFilters()
    {
        return [];
    }

    /**
     * Create a new asset category.
     *
     * @param array $params
     * @return AssetCategory
     */
    public function create($params)
    {
        $asset_category = $this->asset_category->forceCreate($this->formatParams($params));

        return $asset_category;
    }

    /**
     * Prepare given params for inserting into database.
     *
     * @param array $params
     * @param integer $asset_category_id
     * @return array
     */
    private function formatParams($params, $asset_category_id = null)
    {
        $name        = gv($params, 'name');
        $description = gv($params, 'description');

        $formatted = [
            'name'        => $name,
            'description' => $description
        ];

        $options = array();

        $formatted['options'] = $options;
        return $formatted;
    }

    /**
     * Update given asset category.
     *
     * @param AssetCategory $asset_category
     * @param array $params
     *
     * @return AssetCategory
     */
    public function update(AssetCategory $asset_category, $params)
    {
        $asset_category->forceFill($this->formatParams($params, $asset_category->id))->save();

        return $asset_category;
    }

    /**
     * Find whether asset category is deletable or not.
     *
     * @param integer $id
     * @return AssetCategory $asset_category
     */
    public function deletable($id)
    {
        $asset_category = $this->findOrFail($id);
        
        if ($this->asset_category->items()->count()) {
            throw ValidationException::withMessages(['message' => trans('employee_asset.asset_category_associated_with_item')]);
        }

        return $asset_category;
    }

    /**
     * Delete asset category.
     *
     * @param AssetCategory $asset_category
     * @return bool|null
     */
    public function delete(AssetCategory $asset_category)
    {
        return $asset_category->delete();
    }

    /**
     * Delete multiple asset categories.
     *
     * @param array $ids
     * @return bool|null
     */
    public function deleteMultiple($ids)
    {
        return $this->asset_category->whereIn('id', $ids)->delete();
    }
}