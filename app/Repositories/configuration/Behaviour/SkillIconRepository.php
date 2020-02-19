<?php

namespace App\Repositories\Configuration\Behaviour;

use Illuminate\Validation\ValidationException;
use App\Models\Configuration\Behaviour\SkillIcon;

class SkillIconRepository
{
    protected $skill_icon;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(
        SkillIcon $skill_icon
    ) {
        $this->skill_icon = $skill_icon;
    }

    /**
     * Get skill icon query
     *
     * @return SkillIcon query
     */
    public function getQuery()
    {
        return $this->skill_icon;
    }

    /**
     * Count skill icon
     *
     * @return integer
     */
    public function count()
    {
        return $this->skill_icon->count();
    }

    /**
     * List all skill icons by name & id
     *
     * @return array
     */
    public function listAll()
    {
        return $this->skill_icon->all()->pluck('name', 'id')->all();
    }

    /**
     * List all skill icons by name & id for select option
     *
     * @return array
     */

    public function selectAll()
    {
        return $this->skill_icon->all(['name', 'id']);
    }

    /**
     * List all skill icons by id
     *
     * @return array
     */
    public function listId()
    {
        return $this->skill_icon->all()->pluck('id')->all();
    }

    /**
     * Get all skill icons
     *
     * @return array
     */
    public function getAll()
    {
        return $this->skill_icon->all();
    }

    /**
     * Find skill icon with given id.
     *
     * @param integer $id
     * @return SkillIcon
     */
    public function find($id)
    {
        return $this->skill_icon->find($id);
    }

    /**
     * Find skill icon with given id or throw an error.
     *
     * @param integer $id
     * @return SkillIcon
     */
    public function findOrFail($id)
    {
        $skill_icon = $this->skill_icon->find($id);

        if (! $skill_icon) {
            throw ValidationException::withMessages(['message' => trans('calendar.could_not_find_skill_icon')]);
        }

        return $skill_icon;
    }

    /**
     * Get all filtered data
     *
     * @param array $params
     * @return SkillIcon
     */
    public function getData($params)
    {
        $sort_by = gv($params, 'sort_by', 'name');
        $order   = gv($params, 'order', 'asc');

        return $this->skill_icon->orderBy($sort_by, $order);
    }

    /**
     * Paginate all skill icon using given params.
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
     * @return SkillIcon
     */
    public function print($params)
    {
        return $this->getData($params)->get();
    }

    /**
     * Create a new skill icon.
     *
     * @param array $params
     * @return SkillIcon
     */
    public function create($params)
    {
        return $this->skill_icon->forceCreate($this->formatParams($params));
    }

    /**
     * Prepare given params for inserting into database.
     *
     * @param array $params
     * @param integer $skill_icon_id
     * @return array
     */
    private function formatParams($params, $skill_icon_id = null)
    {
        $formatted = [
            'name'        => gv($params, 'name'),
        ];

        $formatted['options'] = [];

        return $formatted;
    }

    /**
     * Update given skill icon.
     *
     * @param SkillIcon $skill_icon
     * @param array $params
     *
     * @return SkillIcon
     */
    public function update(SkillIcon $skill_icon, $params)
    {
        return $skill_icon->forceFill($this->formatParams($params, $skill_icon->id))->save();
    }

    /**
     * Find skill icon & check it can be deleted or not.
     *
     * @param integer $id
     * @return SkillIcon
     */
    public function deletable($id)
    {
        $skill_icon = $this->findOrFail($id);

        return $skill_icon;
    }

    /**
     * Delete skill icon.
     *
     * @param integer $id
     * @return bool|null
     */
    public function delete(SkillIcon $skill_icon)
    {
        return $skill_icon->delete();
    }

    /**
     * Delete multiple skill icons.
     *
     * @param array $ids
     * @return bool|null
     */
    public function deleteMultiple($ids)
    {
        return $this->skill_icon->whereIn('id', $ids)->delete();
    }
}
