<?php
namespace App\Repositories\Exam;

use App\Models\Exam\Exam;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Repositories\Configuration\Exam\TermRepository;

class ExamRepository
{
    protected $exam;
    protected $exam_term;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(
        Exam $exam,
        TermRepository $exam_term
    ) {
        $this->exam = $exam;
        $this->exam_term = $exam_term;
    }

    /**
     * Get exam query
     *
     * @return Exam query
     */
    public function getQuery()
    {
        return $this->exam;
    }

    /**
     * Count exam
     *
     * @return integer
     */
    public function count()
    {
        return $this->exam->filterBySession()->count();
    }

    /**
     * List all exams by name & id
     *
     * @return array
     */
    public function listAll()
    {
        return $this->exam->filterBySession()->get()->pluck('name', 'id')->all();
    }

    /**
     * List all exams by name & id for select option
     *
     * @return array
     */

    public function selectAll()
    {
        return $this->exam->filterBySession()->get(['name', 'id']);
    }

    /**
     * List all exams by id
     *
     * @return array
     */
    public function listId()
    {
        return $this->exam->filterBySession()->get()->pluck('id')->all();
    }

    /**
     * Get all exams
     *
     * @return array
     */
    public function getAll()
    {
        return $this->exam->filterBySession()->get();
    }

    /**
     * Find exam with given id.
     *
     * @param integer $id
     * @return Exam
     */
    public function find($id)
    {
        return $this->exam->info()->filterBySession()->filterById($id)->first();
    }

    /**
     * Find exam with given id or throw an error.
     *
     * @param integer $id
     * @return Exam
     */
    public function findOrFail($id, $field = 'message')
    {
        $exam = $this->exam->info()->filterBySession()->filterById($id)->first();

        if (! $exam) {
            throw ValidationException::withMessages([$field => trans('exam.could_not_find')]);
        }

        return $exam;
    }

    /**
     * Get all filtered data
     *
     * @param array $params
     * @return Exam
     */
    public function getData($params)
    {
        $sort_by = gv($params, 'sort_by', 'position');
        $order   = gv($params, 'order', 'asc');
        $name    = gv($params, 'name');

        $query = $this->exam->info()->filterBySession()->filterByName($name);

        return $query->orderBy($sort_by, $order);
    }

    /**
     * Paginate all exams using given params.
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
     * @return Exam
     */
    public function print($params)
    {
        return $this->getData($params)->get();
    }

    /**
     * Get exam pre requisite.
     *
     * @return Array
     */
    public function getPreRequisite()
    {
        $exam_terms = $this->exam_term->selectAll();

        return compact('exam_terms');
    }

    /**
     * Create a new exam.
     *
     * @param array $params
     * @return Exam
     */
    public function create($params)
    {
        return $this->exam->forceCreate($this->formatParams($params));
    }

    /**
     * Prepare given params for inserting into database.
     *
     * @param array $params
     * @param string $type
     * @return array
     */
    private function formatParams($params, $exam_id = null)
    {
        $name = gv($params, 'name');
        $description = gv($params, 'description');
        $exam_term_id = gv($params, 'exam_term_id');

        if ($exam_term_id) {
            $exam_term = $this->exam_term->findOrFail($exam_term_id);
        }

        $query = (! $exam_id) ? $this->exam : $this->exam->where('id', '!=', $exam_id);

        $exam_exists = $query->filterByName($name, 1)->filterBySession()->count();

        if ($exam_exists) {
            throw ValidationException::withMessages(['name' => trans('exam.exam_exists')]);
        }

        $formatted = [
            'exam_term_id' => $exam_term_id,
            'name'         => $name,
            'description'  => $description,
            'options'      => []
        ];

        if (! $exam_id) {
            $formatted['academic_session_id'] = config('config.default_academic_session.id');
        }

        return $formatted;
    }

    /**
     * Update given exam.
     *
     * @param Exam $exam
     * @param array $params
     *
     * @return Exam
     */
    public function update(Exam $exam, $params)
    {
        return $exam->forceFill($this->formatParams($params, $exam->id))->save();
    }

    /**
     * Reorder all exam
     *
     * @param array $params
     */
    public function reorder($params)
    {
        $list = gv($params, 'list', []);
        foreach ($list as $index => $item) {
            $exam = $this->exam->filterBySession()->filterByName($item, 1)->first();
            $exam->position = $index;
            $exam->save();
        }
    }

    /**
     * Find exam & check it can be deleted or not.
     *
     * @param integer $id
     * @return Exam
     */
    public function deletable($id)
    {
        $exam = $this->findOrFail($id);

        if ($exam->schedules()->count()) {
            throw ValidationException::withMessages(['message' => trans('exam.exam_associated_with_exam_schedule')]);
        }

        return $exam;
    }

    /**
     * Delete exam.
     *
     * @param integer $id
     * @return bool|null
     */
    public function delete(Exam $exam)
    {
        return $exam->delete();
    }

    /**
     * Delete multiple exams.
     *
     * @param array $ids
     * @return bool|null
     */
    public function deleteMultiple($ids)
    {
        return $this->exam->whereIn('id', $ids)->delete();
    }
}
