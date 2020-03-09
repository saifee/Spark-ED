<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\EmployeeBehaviourPointRequest;
use App\Models\Employee\Employee;
use App\Repositories\Employee\BehaviourRepository;

class BehaviourController extends Controller
{
    protected $repo;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(
        BehaviourRepository $repo
    ) {
        $this->repo = $repo;
    }

    /**
     * Used to get pre requisites
     * @get ("/api/course/pre-requisite")
     * @return Response
     */
    public function preRequisite()
    {
        $this->authorize('preRequisite', Employee::class);

        return $this->ok($this->repo->getPreRequisite());
    }

    public function store(EmployeeBehaviourPointRequest $request)
    {
        // $this->authorize('create', EmployeeBehaviourPoint::class);

        $registration = $this->repo->create($request->all());

        return $this->success(['message' => trans('behaviour.feedback_complete', [
            'skill_points' => $request->input('skill_points'),
        ])]);
    }
}
