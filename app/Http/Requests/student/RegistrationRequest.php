<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
        if (request('student_type') == 'new') {
            return [
                'first_name'              => 'required|min:2',
                'date_of_birth'           => 'required|date',
                'date_of_registration'    => 'required|date|after:date_of_birth',
                'contact_number'          => 'required',
                'gender'                  => 'required',
                'father_name'             => 'required_if:parent_type,new|min:3',
                'mother_name'             => 'required_if:parent_type,new|min:3',
                'father_contact_number_1' => 'required_if:parent_type,new',
                'course_id'               => 'required'
            ];
        } else {
            return [
                'student_id'              => 'required',
                'date_of_registration'    => 'required|date|after:date_of_birth',
                'course_id'               => 'required'
            ];
        }
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'student_id'           => trans('student.student'),
            'first_name'           => trans('student.first_name'),
            'last_name'            => trans('student.last_name'),
            'date_of_birth'        => trans('student.date_of_birth'),
            'date_of_registration' => trans('student.date_of_registration'),
            'contact_number'       => trans('student.contact_number'),
            'gender'               => trans('student.gender'),
            'father_name'          => trans('student.father_name'),
            'mother_name'          => trans('student.mother_name'),
            'course_id'            => trans('academic.course')
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'father_name.required_if'             => trans('validation.required', ['attribute' => trans('student.father_name')]),
            'mother_name.required_if'             => trans('validation.required', ['attribute' => trans('student.mother_name')]),
            'father_contact_number_1.required_if' => trans('validation.required', ['attribute' => trans('student.father_contact_number_1')])
        ];
    }
}
