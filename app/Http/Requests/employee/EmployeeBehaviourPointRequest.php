<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeBehaviourPointRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'employee_term_id'  => 'required',
            'employee_skill_id' => 'required',
            'employee_skill_points'      => 'required',
        ];
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'employee_term_id'  => trans('employee.employee'),
            'employee_skill_id' => trans('behaviour.employee_skill_id'),
            'employee_skill_points'      => trans('behaviour.employee_skill_points'),
        ];
    }
}
