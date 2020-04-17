<?php
namespace App\Repositories\Configuration\Employee;

use App\Models\Configuration\Employee\AssetItem;
use App\Repositories\Configuration\Employee\AssetCategoryRepository;
use Illuminate\Validation\ValidationException;

class AssetItemRepository
{
    protected $asset_item;
    protected $asset_category;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(
        AssetItem $asset_item,
        AssetCategoryRepository $asset_category
    ) {
        $this->asset_item = $asset_item;
        $this->asset_category = $asset_category;
    }

    /**
     * Get asset item query
     *
     * @return AssetItem query
     */
    public function getQuery()
    {
        return $this->asset_item;
    }

    /**
     * Count asset item
     *
     * @return integer
     */
    public function count()
    {
        return $this->asset_item->count();
    }

    /**
     * Get all asset items
     *
     * @return array
     */
    public function getAll()
    {
        return $this->asset_item->all();
    }

    /**
     * List all asset items by name & id for select option
     *
     * @return array
     */

    public function selectAll()
    {
        $asset_items = $this->asset_item->get(['name', 'id', 'serial_number']);

        $data = array();
        foreach ($asset_items as $asset_item) {
            $data[] = array(
                'name' => ($asset_item->serial_number) ? $asset_item->name.' ('.$asset_item->serial_number.')' : $asset_item->name,
                'id' => $asset_item->id,
            );
        }

        return $data;
    }

    /**
     * List all asset items by id
     *
     * @return array
     */
    public function listId()
    {
        return $this->asset_item->get()->pluck('id')->all();
    }

    /**
     * Find asset item with given id.
     *
     * @param integer $id
     * @return AssetItem
     */
    public function find($id)
    {
        return $this->asset_item->info()->find($id);
    }

    /**
     * Find asset item with given id or throw an error.
     *
     * @param integer $id
     * @return AssetItem
     */
    public function findOrFail($id, $field = 'message')
    {
        $asset_item = $this->asset_item->info()->find($id);

        if (! $asset_item) {
            throw ValidationException::withMessages([$field => trans('employee_asset.could_not_find_asset_item')]);
        }

        return $asset_item;
    }

    /**
     * Get all filtered data
     *
     * @param array $params
     * @return AssetItem
     */
    public function getData($params)
    {
        $sort_by           = gv($params, 'sort_by', 'name');
        $order             = gv($params, 'order', 'desc');
        $name              = gv($params, 'name');
        $asset_category_id = gv($params, 'asset_category_id');
        $asset_category_id = is_array($asset_category_id) ? $asset_category_id : ($asset_category_id ? explode(',', $asset_category_id) : []);

        $query = $this->asset_item->info();

        if (count($asset_category_id)) {
            $query->whereIn('asset_category_id', $asset_category_id);
        }

        return $query->filterByName($name)->orderBy($sort_by, $order);
    }

    /**
     * Paginate all asset items using given params.
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
     * @return AssetItem
     */
    public function print($params)
    {
        return $this->getData($params)->get();
    }

    /**
     * Get asset item pre requisite.
     *
     * @return Array
     */
    public function getPreRequisite()
    {
        $asset_categories = $this->asset_category->selectAll();

        return compact('asset_categories');
    }

    /**
     * Get asset item filters.
     *
     * @return Array
     */
    public function getFilters()
    {
        return $this->getPreRequisite();
    }

    /**
     * Create a new asset item.
     *
     * @param array $params
     * @return AssetItem
     */
    public function create($params)
    {
        $asset_item = $this->asset_item->forceCreate($this->formatParams($params));

        return $asset_item;
    }

    /**
     * Prepare given params for inserting into database.
     *
     * @param array $params
     * @param integer $asset_item_id
     * @return array
     */
    private function formatParams($params, $asset_item_id = null)
    {
        $name              = gv($params, 'name');
        $serial_number     = gv($params, 'serial_number');
        $model_number      = gv($params, 'model_number');
        $price             = gv($params, 'price', 0);
        $date              = gv($params, 'date');
        $description       = gv($params, 'description');
        $image             = gv($params, 'image');
        $asset_category_id = gv($params, 'asset_category_id');

        $asset_category = $this->asset_category->findOrFail($asset_category_id);

        $formatted = [
            'name'              => $name,
            'serial_number'     => $serial_number,
            'model_number'      => $model_number,
            'price'             => $price,
            'date'              => $date,
            'description'       => $description,
            'image'             => $image,
            'asset_category_id' => $asset_category_id,
        ];

        $options = array();

        $formatted['options'] = $options;
        return $formatted;
    }

    /**
     * Update given asset item.
     *
     * @param AssetItem $asset_item
     * @param array $params
     *
     * @return AssetItem
     */
    public function update(AssetItem $asset_item, $params)
    {
        $asset_item->forceFill($this->formatParams($params, $asset_item->id))->save();

        return $asset_item;
    }

    /**
     * Find whether asset item is deletable or not.
     *
     * @param integer $id
     * @return AssetItem $asset_item
     */
    public function deletable($id)
    {
        $asset_item = $this->findOrFail($id);
        
        return $asset_item;
    }

    /**
     * Delete asset item.
     *
     * @param AssetItem $asset_item
     * @return bool|null
     */
    public function delete(AssetItem $asset_item)
    {
        return $asset_item->delete();
    }

    /**
     * Delete multiple asset items.
     *
     * @param array $ids
     * @return bool|null
     */
    public function deleteMultiple($ids)
    {
        return $this->asset_item->whereIn('id', $ids)->delete();
    }
}