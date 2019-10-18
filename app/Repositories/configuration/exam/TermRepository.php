<?php
namespace App\Repositories\Configuration\Exam;

use Illuminate\Validation\ValidationException;
use App\Models\Configuration\Exam\Term;

class TermRepository
{
    protected $exam_term;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(
        Term $exam_term
    ) {
        $this->exam_term = $exam_term;
    }

    /**
     * Get exam term query
     *
     * @return Term query
     */
    public function getQuery()
    {
        return $this->exam_term;
    }

    /**
     * Count exam term
     *
     * @return integer
     */
    public function count()
    {
        return $this->exam_term->filterBySession()->count();
    }

    /**
     * List all exam terms by name & id
     *
     * @return array
     */
    public function listAll()
    {
        return $this->exam_term->filterBySession()->get()->pluck('name', 'id')->all();
    }

    /**
     * List all exam terms by name & id for select option
     *
     * @return array
     */

    public function selectAll()
    {
        return $this->exam_term->filterBySession()->get(['name', 'id']);
    }

    /**
     * List all exam terms by id
     *
     * @return array
     */
    public function listId()
    {
        return $this->exam_term->filterBySession()->get()->pluck('id')->all();
    }

    /**
     * Get all exam terms
     *
     * @return array
     */
    public function getAll()
    {
        return $this->exam_term->info()->filterBySession()->get();
    }

    /**
     * Find exam term with given id.
     *
     * @param integer $id
     * @return Term
     */
    public function find($id)
    {
        return $this->exam_term->info()->filterBySession()->find($id);
    }

    /**
     * Find exam term with given id or throw an error.
     *
     * @param integer $id
     * @return Term
     */
    public function findOrFail($id, $field = 'message')
    {
        $exam_term = $this->exam_term->info()->filterBySession()->find($id);

        if (! $exam_term) {
            throw ValidationException::withMessages([$field => trans('exam.could_not_find_term')]);
        }

        return $exam_term;
    }

    /**
     * Get all filtered data
     *
     * @param array $params
     * @return Term
     */
    public function getData($params)
    {
        $sort_by     = gv($params, 'sort_by', 'position');
        $order       = gv($params, 'order', 'asc');

        return $this->exam_term->info()->filterBySession()->orderBy($sort_by, $order);
    }

    /**
     * Paginate all exam terms using given params.
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
     * @return Term
     */
    public function print($params)
    {
        return $this->getData($params)->get();
    }

    /**
     * Reorder all exam term
     *
     * @param array $params
     */
    public function reorder($params)
    {
        $list = gv($params, 'list', []);
        foreach ($list as $index => $item) {
            $exam_term = $this->exam_term->filterBySession()->filterByName($item, 1)->first();
            $exam_term->position = $index;
            $exam_term->save();
        }
    }

    /**
     * Create a new exam term.
     *
     * @param array $params
     * @return Term
     */
    public function create($params)
    {
        return $this->exam_term->forceCreate($this->formatParams($params));
    }

    /**
     * Prepare given params for inserting into database.
     *
     * @param array $params
     * @param string $type
     * @return array
     */
    private function formatParams($params, $exam_term_id = null)
    {
        $exam_term_exist_query = ($exam_term_id) ? $this->exam_term->filterBySession()->where('id', '!=', $exam_term_id) : $this->exam_term->filterBySession();

        if ($exam_term_exist_query->filterByName(gv($params, 'name'), 1)->count()) {
            throw ValidationException::withMessages(['name' => trans('validation.unique', ['attribute' => trans('exam.term_name')])]);
        }

        $formatted = [
            'name'        => gv($params, 'name'),
            'description' => gv($params, 'description')
        ];

        if (! $exam_term_id) {
            $formatted['academic_session_id'] = config('config.default_academic_session.id');
        }
        
        $formatted['options'] = [];

        return $formatted;
    }

    /**
     * Update given exam term.
     *
     * @param Term $exam_term
     * @param array $params
     *
     * @return Term
     */
    public function update(Term $exam_term, $params)
    {
        return $exam_term->forceFill($this->formatParams($params, $exam_term->id))->save();
    }

    /**
     * Find exam term & check it can be deleted or not.
     *
     * @param integer $id
     * @return Term
     */
    public function deletable($id)
    {
        $exam_term = $this->findOrFail($id);

        return $exam_term;
    }

    /**
     * Delete exam term.
     *
     * @param integer $id
     * @return bool|null
     */
    public function delete(Term $exam_term)
    {
        return $exam_term->delete();
    }

    /**
     * Delete multiple exam terms.
     *
     * @param array $ids
     * @return bool|null
     */
    public function deleteMultiple($ids)
    {
        return $this->exam_term->whereIn('id', $ids)->delete();
    }
}
