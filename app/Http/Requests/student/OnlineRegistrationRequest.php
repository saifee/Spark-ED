<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class OnlineRegistrationRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'first_name' => 'required|min:2',
			'last_name' => 'required|min:2',
			'date_of_birth' => 'required|date',
			'contact_number' => 'required',
			'gender' => 'required',
			'father_name' => 'required',
			'mother_name' => 'required',
			'father_contact_number_1' => 'required',
			'course_id' => 'required',
			'address_line_1' => 'required',
			'city' => 'required',
			'state' => 'required',
			'zipcode' => 'required',
			'country' => 'required',
		];
	}

	/**
	 * Translate fields with user friendly name.
	 *
	 * @return array
	 */
	public function attributes() {
		return [
			'first_name' => trans('student.first_name'),
			'last_name' => trans('student.last_name'),
			'date_of_birth' => trans('student.date_of_birth'),
			'contact_number' => trans('student.contact_number'),
			'gender' => trans('student.gender'),
			'father_name' => trans('student.father_name'),
			'mother_name' => trans('student.mother_name'),
			'father_contact_number_1' => trans('student.father_contact_number'),
			'course_id' => trans('academic.course'),
			'address_line_1' => trans('student.address_line_1'),
			'city' => trans('student.city'),
			'state' => trans('student.state'),
			'zipcode' => trans('student.zipcode'),
			'country' => trans('student.country'),
		];
	}
}
