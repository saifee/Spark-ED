<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StudentBehaviourPointRequest extends FormRequest
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
            'student_record_id' => 'required',
            'skill_id'          => 'required',
            'skill_points'      => 'required',
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
            'student_record_id' => trans('student.student'),
            'skill_id'          => trans('behaviour.skill_id'),
            'skill_points'      => trans('behaviour.skill_points'),
        ];
    }
}
