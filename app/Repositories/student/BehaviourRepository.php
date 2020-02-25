<?php
namespace App\Repositories\Student;

use App\Models\Student\StudentBehaviourPointLog;
use App\Models\Student\StudentRecord;
use App\Repositories\Configuration\Behaviour\SkillRepository;
use Illuminate\Validation\ValidationException;

class BehaviourRepository
{
    protected $skill;
    protected $student_record;
    protected $student_behaviour_point_log;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(
        StudentRecord $student_record,
        StudentBehaviourPointLog $student_behaviour_point_log,
        SkillRepository $skill
    ) {
        $this->student_record = $student_record;
        $this->student_behaviour_point_log = $student_behaviour_point_log;
        $this->skill = $skill;
    }

    /**
     * Get course pre requisite.
     *
     * @return Array
     */
    public function getPreRequisite()
    {
        return $this->skill->getAll();
    }

    public function create($params)
    {
        $params = $this->validateInput($params);

        $student_record_id = gv($params, 'student_record_id');
        $skill_points      = gv($params, 'skill_points');
        $skill_id          = gv($params, 'skill_id');

        if (! $student_record_id) {
            throw ValidationException::withMessages(['message' => trans('validation.required', ['attribute' => trans('student.student')])]);
        }

        $student_record = $this->student_record->filterbyId($student_record_id)->first();

        if (! $student_record) {
            throw ValidationException::withMessages(['message' => trans('student.could_not_find_student')]);
        }

        \DB::beginTransaction();
        $student_behaviour_point = $student_record->student_behaviour_point;
        if (!$student_behaviour_point) {
            $student_record->student_behaviour_point()->create([
                'student_record_id' => $student_record_id,
                'total'             => $skill_points,
                'options'           => [],
            ]);
        } else {
            $student_record->student_behaviour_point()->increment('total', $skill_points);
        }

        // $options = $student_behaviour_point->options;
        // $student_behaviour_point->options = $options;
        // $student_behaviour_point->save();

        $student_behaviour_point_log = $this->student_behaviour_point_log->forceCreate([
            'employee_id' => optional(auth()->user()->employee())->id,
            'skill_id' => $skill_id,
            'points' => $skill_points,
            'options' => [],
        ]);
        \DB::commit();
    }

    public function validateInput($params = array())
    {
        // $student_record_id = gv($params, 'student_record_id');
        // $skill_points      = gv($params, 'skill_points');
        // $skill_id          = gv($params, 'skill_id');

        return $params;
    }
}
