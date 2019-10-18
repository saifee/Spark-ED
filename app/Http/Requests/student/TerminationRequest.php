<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class TerminationRequest extends FormRequest
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
            'date_of_termination' => 'required|date_format:Y-m-d',
            'termination_remarks' => 'required|min:20'
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
            'date_of_termination' => trans('student.date_of_termination'),
            'termination_remarks' => trans('student.termination_remarks')
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
        ];
    }
}
