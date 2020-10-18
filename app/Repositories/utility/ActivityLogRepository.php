<?php
namespace App\Repositories\Utility;

use App\Models\Utility\ActivityLog;
use Illuminate\Validation\ValidationException;

class ActivityLogRepository
{
    protected $activity_log;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(
        ActivityLog $activity_log
    ) {
        $this->activity_log = $activity_log;
    }

    /**
     * Find activity log with given id or throw an error.
     *
     * @param integer $id
     * @return ActivityLog
     */
    public function findOrFail($id)
    {
        $activity_log = $this->activity_log->find($id);

        if (! $activity_log) {
            throw ValidationException::withMessages(['message' => trans('utility.could_not_find_activity_log')]);
        }

        return $activity_log;
    }

    /**
     * Paginate all activity logs using given params.
     *
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($params)
    {
        $sort_by     = gv($params, 'sort_by', 'created_at');
        $order       = gv($params, 'order', 'desc');
        $page_length = gv($params, 'page_length', config('config.page_length'));
        $user_id    = gv($params, 'user_id');
        $start_date = gv($params, 'start_date');
        $end_date   = gv($params, 'end_date');
        $description = gv($params, 'description');

        return $this->activity_log
            ->filterByUserId($user_id)
            ->dateBetween([
                'start_date' => $start_date,
                'end_date' => $end_date
            ])
            ->filterByDescription($description)
            ->orderBy($sort_by, $order)
            ->paginate($page_length);
    }

    /**
     * Record a new activity log.
     *
     * @param array $params
     * @return ActivityLog
     */
    public function record($params)
    {
        return $this->activity_log->forceCreate($this->formatParams($params));
    }

    /**
     * Prepare given params for inserting into database.
     *
     * @param array $params
     * @param string $type
     * @return array
     */
    private function formatParams($params)
    {
        $formatted = [
            'to_address'   => gv($params, 'to'),
            'subject'      => gv($params, 'subject'),
            'body'         => gv($params, 'body'),
            'from_address' => config('mail.from.address'),
            'module'       => gv($params, 'module'),
            'module_id'    => gv($params, 'module_id')
        ];

        return $formatted;
    }

    /**
     * Delete activity log.
     *
     * @param integer $id
     * @return bool|null
     */
    public function delete(ActivityLog $activity_log)
    {
        return $activity_log->delete();
    }

    /**
     * Delete multiple activity logs.
     *
     * @param array $ids
     * @return bool|null
     */
    public function deleteMultiple($ids)
    {
        return $this->activity_log->whereIn('id', $ids)->delete();
    }
}
