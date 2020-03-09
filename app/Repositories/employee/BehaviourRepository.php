<?php
namespace App\Repositories\Employee;

use App\Models\Employee\EmployeeBehaviourPointLog;
use App\Models\Employee\EmployeeTerm;
use App\Repositories\Configuration\Behaviour\EmployeeSkillRepository;
use Illuminate\Validation\ValidationException;

class BehaviourRepository
{
    protected $employee_skill;
    protected $employee_term;
    protected $employee_behaviour_point_log;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(
        EmployeeTerm $employee_term,
        EmployeeBehaviourPointLog $employee_behaviour_point_log,
        EmployeeSkillRepository $employee_skill
    ) {
        $this->employee_term = $employee_term;
        $this->employee_behaviour_point_log = $employee_behaviour_point_log;
        $this->employee_skill = $employee_skill;
    }

    /**
     * Get course pre requisite.
     *
     * @return Array
     */
    public function getPreRequisite()
    {
        return $this->employee_skill->getAll();
    }

    public function create($params)
    {
        $params = $this->validateInput($params);

        $employee_skill_id     = gv($params, 'employee_skill_id');
        $employee_term_id      = gv($params, 'employee_term_id');
        $employee_skill_points = gv($params, 'employee_skill_points');

        if (! $employee_term_id) {
            throw ValidationException::withMessages(['message' => trans('validation.required', ['attribute' => trans('student.student')])]);
        }

        $employee_term = $this->employee_term->filterbyId($employee_term_id)->first();

        if (! $employee_term) {
            throw ValidationException::withMessages(['message' => trans('student.could_not_find_student')]);
        }

        \DB::beginTransaction();
        $employee_behaviour_point = $employee_term->employeeBehaviourPoint;
        if (!$employee_behaviour_point) {
            $employee_term->employeeBehaviourPoint()->create([
                'employee_term_id' => $employee_term_id,
                'total'            => $employee_skill_points,
                'options'          => [],
            ]);
        } else {
            $employee_term->employeeBehaviourPoint()->increment('total', $employee_skill_points);
        }

        // $options = $employee_behaviour_point->options;
        // $employee_behaviour_point->options = $options;
        // $employee_behaviour_point->save();

        $employee_behaviour_point_log = $this->employee_behaviour_point_log->forceCreate([
            'employee_term_id' => $employee_term_id,
            'employee_skill_id' => $employee_skill_id,
            'points' => $employee_skill_points,
            'options' => [],
        ]);
        \DB::commit();
    }

    public function validateInput($params = array())
    {
        // $employee_term_id      = gv($params, 'employee_term_id');
        // $employee_skill_points = gv($params, 'employee_skill_points');
        // $employee_skill_id     = gv($params, 'employee_skill_id');

        return $params;
    }
}
