<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            'type'                     => 'required|in:basic,parent,contact',
            'first_name'               => 'required_if:type,basic|min:2',
            'date_of_birth'            => 'required_if:type,basic|date',
            'gender'                   => 'required_if:type,basic',
            'father_name'              => 'required_if:type,parent|min:3',
            'mother_name'              => 'required_if:type,parent|min:3',
            'father_email'             => 'sometimes|email',
            'mother_email'             => 'sometimes|email',
            'father_date_of_birth'     => 'sometimes|date',
            'mother_date_of_birth'     => 'sometimes|date',
            'contact_number'           => 'sometimes|required',
            'email'                    => 'sometimes|email',
            'emergency_contact_number' => 'sometimes|required',
            'present_address_line_1'   => 'sometimes|required',
            'present_city'             => 'sometimes|required',
            'present_state'            => 'sometimes|required',
            'present_zipcode'          => 'sometimes|required'
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
            'first_name'               => trans('student.first_name'),
            'last_name'                => trans('student.last_name'),
            'date_of_birth'            => trans('student.date_of_birth'),
            'gender'                   => trans('student.gender'),
            'father_name'              => trans('student.father_name'),
            'mother_name'              => trans('student.mother_name'),
            'father_email'             => trans('student.father_email'),
            'mother_email'             => trans('student.mother_email'),
            'father_date_of_birth'     => trans('student.father_date_of_birth'),
            'mother_date_of_birth'     => trans('student.mother_date_of_birth'),
            'contact_number'           => trans('student.contact_number'),
            'email'                    => trans('student.email'),
            'emergency_contact_number' => trans('student.emergency_contact_number'),
            'present_address_line_1'   => trans('student.address_line_1'),
            'present_city'             => trans('student.city'),
            'present_state'            => trans('student.state'),
            'present_zipcode'          => trans('student.zipcode')
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
            'first_name.required_if'     => trans('validation.required', ['attribute' => trans('student.first_name')]),
            'last_name.required_if'      => trans('validation.required', ['attribute' => trans('student.last_name')]),
            'date_of_birth.required_if'  => trans('validation.required', ['attribute' => trans('student.date_of_birth')]),
            'gender.required_if'         => trans('validation.required', ['attribute' => trans('student.gender')]),
            'father_name.required_if'    => trans('validation.required', ['attribute' => trans('student.father_name')]),
            'mother_name.required_if'    => trans('validation.required', ['attribute' => trans('student.mother_name')])
        ];
    }
}
