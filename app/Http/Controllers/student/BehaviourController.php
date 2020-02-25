<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StudentBehaviourPointRequest;
use App\Models\Student\Student;
use App\Repositories\Student\BehaviourRepository;

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

        $this->middleware('academic.session.set');
    }

    /**
     * Used to get pre requisites
     * @get ("/api/course/pre-requisite")
     * @return Response
     */
    public function preRequisite()
    {
        $this->authorize('preRequisite', Student::class);

        return $this->ok($this->repo->getPreRequisite());
    }

    public function store(StudentBehaviourPointRequest $request)
    {
        // $this->authorize('create', StudentBehaviourPoint::class);

        $registration = $this->repo->create($request->all());

        return $this->success(['message' => trans('behaviour.feedback_complete', [
            'skill_points' => $request->input('skill_points'),
        ])]);
    }
}
