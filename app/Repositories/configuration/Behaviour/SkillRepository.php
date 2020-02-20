<?php

namespace App\Repositories\Configuration\Behaviour;

use Illuminate\Validation\ValidationException;
use App\Models\Configuration\Behaviour\Skill;

class SkillRepository
{
    protected $skill;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(
        Skill $skill
    ) {
        $this->skill = $skill;
    }

    /**
     * Get skill query
     *
     * @return Skill query
     */
    public function getQuery()
    {
        return $this->skill;
    }

    /**
     * Count skill
     *
     * @return integer
     */
    public function count()
    {
        return $this->skill->filterBySession()->count();
    }

    /**
     * List all skills by name & id
     *
     * @return array
     */
    public function listAll()
    {
        return $this->skill->filterBySession()->get()->pluck('name', 'id')->all();
    }

    /**
     * List all skills by name & id for select option
     *
     * @return array
     */

    public function selectAll()
    {
        return $this->skill->filterBySession()->get(['name', 'id']);
    }

    /**
     * List all skills by id
     *
     * @return array
     */
    public function listId()
    {
        return $this->skill->filterBySession()->get()->pluck('id')->all();
    }

    /**
     * Get all skills
     *
     * @return array
     */
    public function getAll()
    {
        return $this->skill->info()->filterBySession()->get();
    }

    /**
     * Find skill with given id.
     *
     * @param integer $id
     * @return Skill
     */
    public function find($id)
    {
        return $this->skill->info()->filterBySession()->find($id);
    }

    /**
     * Find skill with given id or throw an error.
     *
     * @param integer $id
     * @return Skill
     */
    public function findOrFail($id, $field = 'message')
    {
        $skill = $this->skill->info()->filterBySession()->find($id);

        if (! $skill) {
            throw ValidationException::withMessages([$field => trans('behaviour.could_not_find_skill')]);
        }

        return $skill;
    }

    /**
     * Get all filtered data
     *
     * @param array $params
     * @return Skill
     */
    public function getData($params)
    {
        $sort_by     = gv($params, 'sort_by', 'position');
        $order       = gv($params, 'order', 'asc');

        return $this->skill->info()->filterBySession()->orderBy($sort_by, $order);
    }

    /**
     * Paginate all skills using given params.
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
     * @return Skill
     */
    public function print($params)
    {
        return $this->getData($params)->get();
    }

    /**
     * Reorder all skill
     *
     * @param array $params
     */
    public function reorder($params)
    {
        $list = gv($params, 'list', []);
        foreach ($list as $index => $item) {
            $skill = $this->skill->filterBySession()->filterByName($item, 1)->first();
            $skill->position = $index;
            $skill->save();
        }
    }

    /**
     * Create a new skill.
     *
     * @param array $params
     * @return Skill
     */
    public function create($params)
    {
        return $this->skill->forceCreate($this->formatParams($params));
    }

    /**
     * Prepare given params for inserting into database.
     *
     * @param array $params
     * @param string $type
     * @return array
     */
    private function formatParams($params, $skill_id = null)
    {
        $skill_exist_query = ($skill_id) ? $this->skill->filterBySession()->where('id', '!=', $skill_id) : $this->skill->filterBySession();

        if ($skill_exist_query->filterByName(gv($params, 'name'), 1)->count()) {
            throw ValidationException::withMessages(['name' => trans('behaviour.skill_exists')]);
        }

        $formatted = [
            'name'                 => gv($params, 'name'),
            'positive'             => gv($params, 'positive'),
            'points'               => gv($params, 'points'),
            'skill_icon_id' => gv($params, 'skill_icon_id'),
            'batch_id'      => gv($params, 'batch_id'),
        ];

        if (! $skill_id) {
            $formatted['academic_session_id'] = config('config.default_academic_session.id');
        }

        $formatted['options'] = [];

        return $formatted;
    }

    /**
     * Update given skill.
     *
     * @param Skill $skill
     * @param array $params
     *
     * @return Skill
     */
    public function update(Skill $skill, $params)
    {
        return $skill->forceFill($this->formatParams($params, $skill->id))->save();
    }

    /**
     * Find skill & check it can be deleted or not.
     *
     * @param integer $id
     * @return Skill
     */
    public function deletable($id)
    {
        $skill = $this->findOrFail($id);

        return $skill;
    }

    /**
     * Delete skill.
     *
     * @param integer $id
     * @return bool|null
     */
    public function delete(Skill $skill)
    {
        return $skill->delete();
    }

    /**
     * Delete multiple skills.
     *
     * @param array $ids
     * @return bool|null
     */
    public function deleteMultiple($ids)
    {
        return $this->skill->whereIn('id', $ids)->delete();
    }
}
