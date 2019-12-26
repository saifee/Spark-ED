<?php

namespace App\Http\Controllers\Utility;

use Illuminate\Http\Request;
use App\Repositories\Utility\ActivityLogRepository;
use App\Http\Controllers\Controller;

class ActivityLogController extends Controller
{
    protected $request;
    protected $repo;

    protected $module = 'activity_log';

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, ActivityLogRepository $repo)
    {
        $this->request  = $request;
        $this->repo     = $repo;

        $this->middleware('permission:access-configuration');
        $this->middleware('feature.available:activity_log');
    }

    /**
     * Used to get activity logs
     * @get ("/api/activity-log")
     * @return Response
     */
    public function index()
    {
        return $this->ok($this->repo->paginate($this->request->all()));
    }

    /**
     * Used to get activity log detail
     * @get ("/api/activity-log/{id}")
     * @return Response
     */
    public function show($id)
    {
        return $this->ok($this->repo->findOrFail($id));
    }

    /**
     * Used to delete activity log
     * @delete ("/api/activity-log/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of activity log to be deleted"),
     * })
     * @return Response
     */
    public function destroy($id)
    {
        $activity_log = $this->repo->findOrFail($id);

        $this->repo->delete($activity_log);

        return $this->success(['message' => trans('utility.activity_log_deleted')]);
    }
}
