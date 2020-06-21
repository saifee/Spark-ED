<?php

namespace App\Repositories\Configuration\Behaviour;

use App\Models\Configuration\Behaviour\EmployeeSkill;
use App\Models\Configuration\Behaviour\SkillIcon;
use Illuminate\Validation\ValidationException;

class EmployeeSkillRepository
{
    protected $employee_skill;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(
        EmployeeSkill $employee_skill
    ) {
        $this->employee_skill = $employee_skill;
    }

    /**
     * Get employee_skill query
     *
     * @return EmployeeSkill query
     */
    public function getQuery()
    {
        return $this->employee_skill;
    }

    /**
     * Count employee_skill
     *
     * @return integer
     */
    public function count()
    {
        return $this->employee_skill->count();
    }

    /**
     * List all employee_skills by name & id
     *
     * @return array
     */
    public function listAll()
    {
        return $this->employee_skill->get()->pluck('name', 'id')->all();
    }

    /**
     * List all employee_skills by name & id for select option
     *
     * @return array
     */

    public function selectAll()
    {
        return $this->employee_skill->get(['name', 'id']);
    }

    /**
     * List all employee_skills by id
     *
     * @return array
     */
    public function listId()
    {
        return $this->employee_skill->get()->pluck('id')->all();
    }

    /**
     * Get all employee_skills
     *
     * @return array
     */
    public function getAll()
    {
        return $this->employee_skill->info()->get();
    }

    /**
     * Find employee_skill with given id.
     *
     * @param integer $id
     * @return EmployeeSkill
     */
    public function find($id)
    {
        return $this->employee_skill->info()->find($id);
    }

    /**
     * Find employee_skill with given id or throw an error.
     *
     * @param integer $id
     * @return EmployeeSkill
     */
    public function findOrFail($id, $field = 'message')
    {
        $employee_skill = $this->employee_skill->info()->find($id);

        if (! $employee_skill) {
            throw ValidationException::withMessages([$field => trans('behaviour.could_not_find_skill')]);
        }

        return $employee_skill;
    }

    /**
     * Get all filtered data
     *
     * @param array $params
     * @return EmployeeSkill
     */
    public function getData($params)
    {
        $sort_by     = gv($params, 'sort_by', 'position');
        $order       = gv($params, 'order', 'asc');

        return $this->employee_skill->info()->orderBy($sort_by, $order);
    }

    /**
     * Paginate all employee_skills using given params.
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
     * @return EmployeeSkill
     */
    public function print($params)
    {
        return $this->getData($params)->get();
    }

    /**
     * Reorder all employee_skill
     *
     * @param array $params
     */
    public function reorder($params)
    {
        $list = gv($params, 'list', []);
        foreach ($list as $index => $item) {
            $employee_skill = $this->employee_skill->filterByName($item, 1)->first();
            $employee_skill->position = $index;
            $employee_skill->save();
        }
    }

    /**
     * Create a new employee_skill.
     *
     * @param array $params
     * @return EmployeeSkill
     */
    public function create($params)
    {
        return $this->employee_skill->forceCreate($this->formatParams($params));
    }

    /**
     * Prepare given params for inserting into database.
     *
     * @param array $params
     * @param string $type
     * @return array
     */
    private function formatParams($params, $employee_skill_id = null)
    {
        $employee_skill_exist_query = ($employee_skill_id) ? $this->employee_skill->where('id', '!=', $employee_skill_id) : $this->employee_skill;

        if ($employee_skill_exist_query->filterByName(gv($params, 'name'), 1)->count()) {
            throw ValidationException::withMessages(['name' => trans('behaviour.skill_exists')]);
        }

        $default_skill_icon = SkillIcon::firstOrCreate(['name' => 'select_all']);

        $formatted = [
            'name'          => gv($params, 'name'),
            'positive'      => gbv($params, 'positive', 0),
            'points'        => gv($params, 'points'),
            'skill_icon_id' => gv($params, 'skill_icon_id', $default_skill_icon->id),
        ];

        $formatted['options'] = [];

        return $formatted;
    }

    /**
     * Update given employee_skill.
     *
     * @param EmployeeSkill $employee_skill
     * @param array $params
     *
     * @return EmployeeSkill
     */
    public function update(EmployeeSkill $employee_skill, $params)
    {
        return $employee_skill->forceFill($this->formatParams($params, $employee_skill->id))->save();
    }

    /**
     * Find employee_skill & check it can be deleted or not.
     *
     * @param integer $id
     * @return EmployeeSkill
     */
    public function deletable($id)
    {
        $employee_skill = $this->findOrFail($id);

        return $employee_skill;
    }

    /**
     * Delete employee_skill.
     *
     * @param integer $id
     * @return bool|null
     */
    public function delete(EmployeeSkill $employee_skill)
    {
        return $employee_skill->delete();
    }

    /**
     * Delete multiple employee_skills.
     *
     * @param array $ids
     * @return bool|null
     */
    public function deleteMultiple($ids)
    {
        return $this->employee_skill->whereIn('id', $ids)->delete();
    }
}
