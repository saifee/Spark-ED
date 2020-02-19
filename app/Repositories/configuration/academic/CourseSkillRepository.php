<?php

namespace App\Repositories\Configuration\Academic;

use Illuminate\Validation\ValidationException;
use App\Models\Configuration\Academic\CourseSkill;

class CourseSkillRepository
{
    protected $course_skill;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(
        CourseSkill $course_skill
    ) {
        $this->course_skill = $course_skill;
    }

    /**
     * Get course skill query
     *
     * @return CourseSkill query
     */
    public function getQuery()
    {
        return $this->course_skill;
    }

    /**
     * Count course skill
     *
     * @return integer
     */
    public function count()
    {
        return $this->course_skill->filterBySession()->count();
    }

    /**
     * List all course skills by name & id
     *
     * @return array
     */
    public function listAll()
    {
        return $this->course_skill->filterBySession()->get()->pluck('name', 'id')->all();
    }

    /**
     * List all course skills by name & id for select option
     *
     * @return array
     */

    public function selectAll()
    {
        return $this->course_skill->filterBySession()->get(['name', 'id']);
    }

    /**
     * List all course skills by id
     *
     * @return array
     */
    public function listId()
    {
        return $this->course_skill->filterBySession()->get()->pluck('id')->all();
    }

    /**
     * Get all course skills
     *
     * @return array
     */
    public function getAll()
    {
        return $this->course_skill->info()->filterBySession()->get();
    }

    /**
     * Find course skill with given id.
     *
     * @param integer $id
     * @return CourseSkill
     */
    public function find($id)
    {
        return $this->course_skill->info()->filterBySession()->find($id);
    }

    /**
     * Find course skill with given id or throw an error.
     *
     * @param integer $id
     * @return CourseSkill
     */
    public function findOrFail($id, $field = 'message')
    {
        $course_skill = $this->course_skill->info()->filterBySession()->find($id);

        if (! $course_skill) {
            throw ValidationException::withMessages([$field => trans('academic.could_not_find_course_skill')]);
        }

        return $course_skill;
    }

    /**
     * Get all filtered data
     *
     * @param array $params
     * @return CourseSkill
     */
    public function getData($params)
    {
        $sort_by     = gv($params, 'sort_by', 'position');
        $order       = gv($params, 'order', 'asc');

        return $this->course_skill->info()->filterBySession()->orderBy($sort_by, $order);
    }

    /**
     * Paginate all course skills using given params.
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
     * @return CourseSkill
     */
    public function print($params)
    {
        return $this->getData($params)->get();
    }

    /**
     * Reorder all course skill
     *
     * @param array $params
     */
    public function reorder($params)
    {
        $list = gv($params, 'list', []);
        foreach ($list as $index => $item) {
            $course_skill = $this->course_skill->filterBySession()->filterByName($item, 1)->first();
            $course_skill->position = $index;
            $course_skill->save();
        }
    }

    /**
     * Create a new course skill.
     *
     * @param array $params
     * @return CourseSkill
     */
    public function create($params)
    {
        return $this->course_skill->forceCreate($this->formatParams($params));
    }

    /**
     * Prepare given params for inserting into database.
     *
     * @param array $params
     * @param string $type
     * @return array
     */
    private function formatParams($params, $course_skill_id = null)
    {
        $course_skill_exist_query = ($course_skill_id) ? $this->course_skill->filterBySession()->where('id', '!=', $course_skill_id) : $this->course_skill->filterBySession();

        if ($course_skill_exist_query->filterByName(gv($params, 'name'), 1)->count()) {
            throw ValidationException::withMessages(['name' => trans('academic.course_skill_exists')]);
        }

        $formatted = [
            'name'                 => gv($params, 'name'),
            'positive'             => gv($params, 'positive'),
            'points'               => gv($params, 'points'),
            'course_skill_icon_id' => gv($params, 'course_skill_icon_id'),
            'course_id'            => gv($params, 'course_id'),
        ];

        if (! $course_skill_id) {
            $formatted['academic_session_id'] = config('config.default_academic_session.id');
        }

        $formatted['options'] = [];

        return $formatted;
    }

    /**
     * Update given course skill.
     *
     * @param CourseSkill $course_skill
     * @param array $params
     *
     * @return CourseSkill
     */
    public function update(CourseSkill $course_skill, $params)
    {
        return $course_skill->forceFill($this->formatParams($params, $course_skill->id))->save();
    }

    /**
     * Find course skill & check it can be deleted or not.
     *
     * @param integer $id
     * @return CourseSkill
     */
    public function deletable($id)
    {
        $course_skill = $this->findOrFail($id);

        if ($course_skill->courses()->count()) {
            throw ValidationException::withMessages(['message' => trans('academic.course_skill_associated_with_course')]);
        }

        return $course_skill;
    }

    /**
     * Delete course skill.
     *
     * @param integer $id
     * @return bool|null
     */
    public function delete(CourseSkill $course_skill)
    {
        return $course_skill->delete();
    }

    /**
     * Delete multiple course skills.
     *
     * @param array $ids
     * @return bool|null
     */
    public function deleteMultiple($ids)
    {
        return $this->course_skill->whereIn('id', $ids)->delete();
    }
}
