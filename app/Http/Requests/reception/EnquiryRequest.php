<?php

namespace App\Http\Requests\Reception;

use Illuminate\Foundation\Http\FormRequest;

class EnquiryRequest extends FormRequest
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
            'father_name'       => 'required',
            'contact_number'    => 'required',
            'email'             => 'email',
            'date_of_enquiry'   => 'required|date_format:Y-m-d',
            'enquiry_type_id'   => 'required',
            'enquiry_source_id' => 'required'
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
            'father_name'       => trans('student.father_name'),
            'contact_number'    => trans('student.contact_number'),
            'email'             => trans('student.email'),
            'date_of_enquiry'   => trans('reception.date_of_enquiry'),
            'enquiry_type_id'   => trans('reception.enquiry_type'),
            'enquiry_source_id' => trans('reception.enquiry_source')
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
