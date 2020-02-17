<?php

namespace App\Repositories\Configuration\Academic;

use Illuminate\Validation\ValidationException;
use App\Models\Configuration\Academic\CourseSkillIcon;

class CourseSkillIconRepository
{
    protected $course_skill_icon;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(
        CourseSkillIcon $course_skill_icon
    ) {
        $this->course_skill_icon = $course_skill_icon;
    }

    /**
     * Get course skill icon query
     *
     * @return CourseSkillIcon query
     */
    public function getQuery()
    {
        return $this->course_skill_icon;
    }

    /**
     * Count course skill icon
     *
     * @return integer
     */
    public function count()
    {
        return $this->course_skill_icon->count();
    }

    /**
     * List all course skill icons by name & id
     *
     * @return array
     */
    public function listAll()
    {
        return $this->course_skill_icon->all()->pluck('name', 'id')->all();
    }

    /**
     * List all course skill icons by name & id for select option
     *
     * @return array
     */

    public function selectAll()
    {
        return $this->course_skill_icon->all(['name', 'id']);
    }

    /**
     * List all course skill icons by id
     *
     * @return array
     */
    public function listId()
    {
        return $this->course_skill_icon->all()->pluck('id')->all();
    }

    /**
     * Get all course skill icons
     *
     * @return array
     */
    public function getAll()
    {
        return $this->course_skill_icon->all();
    }

    /**
     * Find course skill icon with given id.
     *
     * @param integer $id
     * @return CourseSkillIcon
     */
    public function find($id)
    {
        return $this->course_skill_icon->find($id);
    }

    /**
     * Find course skill icon with given id or throw an error.
     *
     * @param integer $id
     * @return CourseSkillIcon
     */
    public function findOrFail($id)
    {
        $course_skill_icon = $this->course_skill_icon->find($id);

        if (! $course_skill_icon) {
            throw ValidationException::withMessages(['message' => trans('calendar.could_not_find_course_skill_icon')]);
        }

        return $course_skill_icon;
    }

    /**
     * Get all filtered data
     *
     * @param array $params
     * @return CourseSkillIcon
     */
    public function getData($params)
    {
        $sort_by = gv($params, 'sort_by', 'name');
        $order   = gv($params, 'order', 'asc');

        return $this->course_skill_icon->orderBy($sort_by, $order);
    }

    /**
     * Paginate all course skill icon using given params.
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
     * @return CourseSkillIcon
     */
    public function print($params)
    {
        return $this->getData($params)->get();
    }

    /**
     * Create a new course skill icon.
     *
     * @param array $params
     * @return CourseSkillIcon
     */
    public function create($params)
    {
        return $this->course_skill_icon->forceCreate($this->formatParams($params));
    }

    /**
     * Prepare given params for inserting into database.
     *
     * @param array $params
     * @param integer $course_skill_icon_id
     * @return array
     */
    private function formatParams($params, $course_skill_icon_id = null)
    {
        $formatted = [
            'name'        => gv($params, 'name'),
        ];

        $formatted['options'] = [];

        return $formatted;
    }

    /**
     * Update given course skill icon.
     *
     * @param CourseSkillIcon $course_skill_icon
     * @param array $params
     *
     * @return CourseSkillIcon
     */
    public function update(CourseSkillIcon $course_skill_icon, $params)
    {
        return $course_skill_icon->forceFill($this->formatParams($params, $course_skill_icon->id))->save();
    }

    /**
     * Find course skill icon & check it can be deleted or not.
     *
     * @param integer $id
     * @return CourseSkillIcon
     */
    public function deletable($id)
    {
        $course_skill_icon = $this->findOrFail($id);

        return $course_skill_icon;
    }

    /**
     * Delete course skill icon.
     *
     * @param integer $id
     * @return bool|null
     */
    public function delete(CourseSkillIcon $course_skill_icon)
    {
        return $course_skill_icon->delete();
    }

    /**
     * Delete multiple course skill icons.
     *
     * @param array $ids
     * @return bool|null
     */
    public function deleteMultiple($ids)
    {
        return $this->course_skill_icon->whereIn('id', $ids)->delete();
    }
}
