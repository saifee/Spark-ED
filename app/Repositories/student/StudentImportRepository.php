<?php
namespace App\Repositories\Student;
ini_set('max_execution_time', 0);

use Illuminate\Support\Str;
use App\Models\Academic\Batch;
use App\Models\Academic\Course;
use App\Models\Student\Student;
use App\Models\Student\Admission;
use App\Models\Student\Registration;
use App\Models\Student\StudentParent;
use App\Models\Student\StudentRecord;
use App\Models\Configuration\Misc\Caste;
use App\Models\Student\StudentFeeRecord;
use App\Models\Finance\Fee\FeeAllocation;
use App\Models\Finance\Fee\FeeConcession;
use App\Models\Transport\TransportCircle;
use App\Models\Configuration\Misc\Category;
use App\Models\Configuration\Misc\Religion;
use App\Models\Configuration\Misc\BloodGroup;
use Illuminate\Validation\ValidationException;

class StudentImportRepository
{
	protected $course;
	protected $batch;
	protected $student;
	protected $parent;
	protected $admission;
	protected $registration;
	protected $student_record;
    protected $blood_group;
    protected $religion;
    protected $category;
    protected $caste;
    protected $fee_concession;
    protected $transport_circle;
    protected $fee_allocation;
    protected $student_fee_record;

	public function __construct(
		Course $course, 
		Batch $batch,
		Student $student,
		StudentParent $parent,
		Admission $admission,
		Registration $registration,
		StudentRecord $student_record,
        BloodGroup $blood_group,
        Religion $religion,
        Category $category,
        Caste $caste,
        FeeConcession $fee_concession,
        TransportCircle $transport_circle,
        FeeAllocation $fee_allocation,
        StudentFeeRecord $student_fee_record
	) {
		$this->course = $course;
		$this->batch = $batch;
		$this->student = $student;
		$this->parent = $parent;
		$this->admission = $admission;
		$this->registration = $registration;
		$this->student_record = $student_record;
        $this->blood_group = $blood_group;
        $this->religion = $religion;
        $this->category = $category;
        $this->caste = $caste;
        $this->fee_concession = $fee_concession;
        $this->transport_circle = $transport_circle;
        $this->fee_allocation = $fee_allocation;
        $this->student_fee_record = $student_fee_record;
	}

	protected $path = '/uploads/temp/student-import/';

	public function getOptions()
	{
        return array(
            [ 'text' => trans('student.admission_number_prefix'), 'value' => "admission_number_prefix"],
            [ 'text' => trans('student.admission_number'), 'value' => "admission_number"],
            [ 'text' => trans('student.date_of_admission'), 'value' => "date_of_admission"],
			[ 'text' => trans('student.first_name'), 'value' => "first_name"],
			[ 'text' => trans('student.middle_name'), 'value' => "middle_name"],
			[ 'text' => trans('student.last_name'), 'value' => "last_name"],
			[ 'text' => trans('student.date_of_birth'), 'value' => "date_of_birth"],
            [ 'text' => trans('student.contact_number'), 'value' => "contact_number"],
			[ 'text' => trans('academic.course'), 'value' => "course"],
			[ 'text' => trans('academic.batch'), 'value' => "batch"],
            [ 'text' => trans('finance.fee_concession'), 'value' => "fee_concession"],
            [ 'text' => trans('transport.transport_circle'), 'value' => "transport_circle"],
			[ 'text' => trans('student.father_name'), 'value' => "father_name"],
			[ 'text' => trans('student.father_contact_number'), 'value' => "father_contact_number"],
			[ 'text' => trans('student.mother_name'), 'value' => "mother_name"],
            [ 'text' => trans('student.gender'), 'value' => "gender"],
            [ 'text' => trans('student.nationality'), 'value' => "nationality"],
            [ 'text' => trans('misc.blood_group'), 'value' => "blood_group"],
            [ 'text' => trans('misc.religion'), 'value' => "religion"],
            [ 'text' => trans('misc.category'), 'value' => "category"],
            [ 'text' => trans('misc.caste'), 'value' => "caste"],
            [ 'text' => trans('student.unique_identification_number'), 'value' => "unique_identification_number"],
            [ 'text' => trans('student.emergency_contact_name'), 'value' => "emergency_contact_name"],
            [ 'text' => trans('student.emergency_contact_number'), 'value' => "emergency_contact_number"],
            [ 'text' => trans('student.address_line_1'), 'value' => "present_address_line_1"],
            [ 'text' => trans('student.address_line_2'), 'value' => "present_address_line_2"],
            [ 'text' => trans('student.city'), 'value' => "present_city"],
            [ 'text' => trans('student.state'), 'value' => "present_state"],
            [ 'text' => trans('student.zipcode'), 'value' => "present_zipcode"],
            [ 'text' => trans('student.country'), 'value' => "present_country"]
        );
	}

    /**
     * Upload file for import
     *
     * @param array $params
     * @return null
     */
    public function startImport($params)
    {
        $extension = request()->file('file')->getClientOriginalExtension();

        if ($extension != 'csv') {
            throw ValidationException::withMessages(['message' => trans('student.import_csv_file_supported')]);
        }

    	$uuid = Str::uuid();
		\Storage::putFileAs($this->path, request()->file('file'), $uuid.'.csv');

        $path = request()->file('file')->getRealPath();
        $items = array_map('str_getcsv', file($path));

        if (count($items) > 500) {
            $this->deleteFile($uuid);
            throw ValidationException::withMessages(['message' => trans('student.max_import_limit', ['number' => 500])]);
        }

        $items = array_slice($items, 1, 2);
        $options = $this->getOptions();

        return compact('items','options','uuid');
    }

    private function deleteFile($uuid)
    {
        \Storage::delete($this->path.$uuid.'.csv');
    }

    /**
     * Finish import
     *
     * @param array $params
     * @return null
     */
    public function finishImport($params)
    {
    	$uuid = gv($params, 'uuid');
    	$all_columns = gv($params, 'columns');

    	$columns = array();
    	foreach ($all_columns as $key => $value) {
            $column_value = gv($value, 'name');

            if (! $column_value) {
                $this->deleteFile($uuid);
                throw ValidationException::withMessages(['message' => trans('student.null_column_found')]);
            }

    		array_push($columns, $column_value);
    	}

    	$options = array();
    	foreach ($this->getOptions() as $key => $value) {
    		array_push($options, gv($value, 'value'));
    	}

    	if (count($columns) != count(array_unique($columns))) {
            $this->deleteFile($uuid);
    		throw ValidationException::withMessages(['message' => trans('student.column_contains_duplicate_field')]);
    	}

    	if (count(array_diff($options, $columns))) {
            $this->deleteFile($uuid);
    		throw ValidationException::withMessages(['message' => trans('student.invalid_column_found')]);
    	}

        if (! \Storage::exists($this->path.$uuid.'.csv')) {
            $this->deleteFile($uuid);
            throw ValidationException::withMessages(['message' => trans('student.could_not_find_import_file')]);
        }

    	$items = array_map('str_getcsv', file(storage_path('app/'.$this->path.$uuid.'.csv')));

    	$courses = $this->course->with('batches')->filterBySession()->get();
    	$students = $this->student->select(['id','first_name','middle_name','last_name','contact_number'])->get();
    	$admissions = $this->admission->select(['id','prefix','number'])->get();
        $student_parents = $this->parent->select(['id','father_name','father_contact_number_1'])->get();
        $blood_groups = $this->blood_group->get();
        $religions = $this->religion->get();
        $categories = $this->category->get();
        $castes = $this->caste->get();
        $transport_circles = $this->transport_circle->filterBySession()->get();
        $fee_concessions = $this->fee_concession->filterBySession()->get();
        $fee_allocations = $this->fee_allocation->with('feeAllocationGroups','feeAllocationGroups.feeInstallments')->filterBySession()->get();

        $list = getVar('list');
        $genders = isset($list['gender']) ? $list['gender'] : [];

    	$existing_admission_numbers = [];
    	foreach ($admissions as $admission){
    	   array_push($existing_admission_numbers, $admission->admission_number);
    	}

    	$existing_student_names = [];
    	foreach ($students as $student) {
    		array_push($existing_student_names, $student->name.' '.$student->contact_number);
    	}

        $student_names                      = [];
        $student_courses                    = [];
        $student_batches                    = [];
        $invalid_date_of_birth              = [];
        $invalid_date_of_admission          = [];
        $date_of_birth_gt_date_of_admission = [];
        $date_of_admission_gt_session_end   = [];
        $missing_father_name                = [];
        $missing_father_contact_number      = [];
        $unknown_courses                    = [];
        $unknown_batches                    = [];
        $duplicate_admission_numbers        = [];
        $unknown_blood_groups               = [];
        $unknown_religions                  = [];
        $unknown_categories                 = [];
        $unknown_castes                     = [];
        $unknown_genders                    = [];
        $unknown_fee_concessions            = [];
        $unknown_transport_circles          = [];

        foreach ($items as $index => $item) {
            if ($index == 0) {
                continue;
            }

            $first_name              = gv($item, array_search('first_name', $columns));
            $middle_name             = gv($item, array_search('middle_name', $columns));
            $last_name               = gv($item, array_search('last_name', $columns));
            $contact_number          = gv($item, array_search('contact_number', $columns));
            $course                  = gv($item, array_search('course', $columns));
            $batch                   = gv($item, array_search('batch', $columns));
            $date_of_birth           = gv($item, array_search('date_of_birth', $columns));
            $date_of_admission       = gv($item, array_search('date_of_admission', $columns));
            $father_name             = gv($item, array_search('father_name', $columns));
            $father_contact_number   = gv($item, array_search('father_contact_number', $columns));
            $admission_number_prefix = gv($item, array_search('admission_number_prefix', $columns));
            $admission_number        = gv($item, array_search('admission_number', $columns));
            $blood_group             = gv($item, array_search('blood_group', $columns));
            $religion                = gv($item, array_search('religion', $columns));
            $category                = gv($item, array_search('category', $columns));
            $caste                   = gv($item, array_search('caste', $columns));
            $gender                  = gv($item, array_search('gender', $columns));
            $fee_concession          = gv($item, array_search('fee_concession', $columns));
            $transport_circle        = gv($item, array_search('transport_circle', $columns));

        	$name = $first_name.' '.($middle_name ? ($middle_name.' ') : '').$last_name;
        	$student_names[] = $name.' '.$contact_number;
        	$student_batches[] = $batch; 

        	if (in_array($name.' '.$contact_number, $existing_student_names)) {
                $this->deleteFile($uuid);
        		throw ValidationException::withMessages(['message' => trans('student.student_already_registered', ['name' => $name])]);
        	}

        	if (! validateDate($date_of_birth)) {
        		array_push($invalid_date_of_birth, $name);
        	}

        	if (! validateDate($date_of_admission)) {
        		array_push($invalid_date_of_admission, $name);
        	}

        	if (validateDate($date_of_birth) && validateDate($date_of_admission) && toDate($date_of_birth) > toDate($date_of_admission))  {
        		array_push($date_of_birth_gt_date_of_admission, $name);
        	}

        	if (validateDate($date_of_admission) && toDate($date_of_admission) > config('config.default_academic_session.end_date')) {
        		array_push($date_of_admission_gt_session_end, $name);
        	}

        	if (! $father_name) {
        		array_push($missing_father_name, $name);
        	}

        	if (! $father_contact_number) {
        		array_push($missing_father_contact_number, $name);
        	}

	    	if (! in_array($course, $courses->pluck('name')->all())) {
	    		array_push($unknown_courses, $course);
	    	}

	    	$batches = optional($courses->firstWhere('name', $course))->batches;

	    	if ($batches && ! in_array($batch, $batches->pluck('name')->all())) {
	    		array_push($unknown_batches, $batch);
	    	}

            if ($blood_group && ! in_array($blood_group, $blood_groups->pluck('name')->all())) {
                array_push($unknown_blood_groups, $blood_group);
            }

            if ($religion && ! in_array($religion, $religions->pluck('name')->all())) {
                array_push($unknown_religions, $religion);
            }

            if ($category && ! in_array($category, $categories->pluck('name')->all())) {
                array_push($unknown_categories, $category);
            }

            if ($caste && ! in_array($caste, $castes->pluck('name')->all())) {
                array_push($unknown_castes, $caste);
            }

            if ($gender && ! in_array($gender, $genders)) {
                array_push($unknown_genders, $gender);
            }

            if ($fee_concession && ! in_array($fee_concession, $fee_concessions->pluck('name')->all())) {
                array_push($unknown_fee_concessions, $fee_concession);
            }

            if ($transport_circle && ! in_array($transport_circle, $transport_circles->pluck('name')->all())) {
                array_push($unknown_transport_circles, $transport_circle);
            }

	    	$admission = $admission_number_prefix.str_pad($admission_number, config('config.admission_number_digit'), '0', STR_PAD_LEFT);
	    	if (in_array($admission, $existing_admission_numbers)) {
	    		array_push($duplicate_admission_numbers, $admission);
	    	}
        }

        if ($unknown_courses) {
            $this->deleteFile($uuid);
        	throw ValidationException::withMessages(['message' => trans('student.unknown_course_found', ['name' => implode(',', array_unique($unknown_courses))])]);
        }

    	if ($unknown_batches) {
            $this->deleteFile($uuid);
    		throw ValidationException::withMessages(['message' => trans('student.unknown_batch_found', ['name' => implode(',', array_unique($unknown_batches))])]);
    	}

        if ($unknown_blood_groups) {
            $this->deleteFile($uuid);
            throw ValidationException::withMessages(['message' => trans('student.unknown_blood_group_found', ['name' => implode(',', array_unique($unknown_blood_groups))])]);
        }

        if ($unknown_religions) {
            $this->deleteFile($uuid);
            throw ValidationException::withMessages(['message' => trans('student.unknown_religion_found', ['name' => implode(',', array_unique($unknown_religions))])]);
        }

        if ($unknown_categories) {
            $this->deleteFile($uuid);
            throw ValidationException::withMessages(['message' => trans('student.unknown_category_found', ['name' => implode(',', array_unique($unknown_categories))])]);
        }

        if ($unknown_castes) {
            $this->deleteFile($uuid);
            throw ValidationException::withMessages(['message' => trans('student.unknown_caste_found', ['name' => implode(',', array_unique($unknown_castes))])]);
        }

        if ($unknown_fee_concessions) {
            $this->deleteFile($uuid);
            throw ValidationException::withMessages(['message' => trans('student.unknown_fee_concession_found', ['name' => implode(',', array_unique($unknown_fee_concessions))])]);
        }

        if ($unknown_transport_circles) {
            $this->deleteFile($uuid);
            throw ValidationException::withMessages(['message' => trans('student.unknown_transport_circle_found', ['name' => implode(',', array_unique($unknown_transport_circles))])]);
        }

        if ($unknown_castes) {
            $this->deleteFile($uuid);
            throw ValidationException::withMessages(['message' => trans('student.unknown_caste_found', ['name' => implode(',', array_unique($unknown_castes))])]);
        }

        if ($unknown_genders) {
            $this->deleteFile($uuid);
            throw ValidationException::withMessages(['message' => trans('student.unknown_gender_found', ['name' => implode(',', array_unique($unknown_genders))])]);
        }

    	if (count($student_names) != count(array_unique($student_names))) {
            $this->deleteFile($uuid);
    		throw ValidationException::withMessages(['message' => trans('student.duplicate_student_found')]);
    	}

    	if ($invalid_date_of_birth) {
            $this->deleteFile($uuid);
    		throw ValidationException::withMessages(['message' => trans('student.invalid_date_of_birth_found', ['name' => implode(',', array_unique($invalid_date_of_birth))])]);
    	}

    	if ($invalid_date_of_admission) {
            $this->deleteFile($uuid);
    		throw ValidationException::withMessages(['message' => trans('student.invalid_date_of_admission_found', ['name' => implode(',', array_unique($invalid_date_of_admission))])]);
    	}

    	if ($date_of_birth_gt_date_of_admission) {
            $this->deleteFile($uuid);
    		throw ValidationException::withMessages(['message' => trans('student.date_of_birth_gt_date_of_admission', ['name' => implode(',', array_unique($date_of_birth_gt_date_of_admission))])]);
    	}

    	if ($date_of_admission_gt_session_end) {
            $this->deleteFile($uuid);
    		throw ValidationException::withMessages(['message' => trans('student.date_of_admission_gt_session_end', ['name' => implode(',', array_unique($date_of_admission_gt_session_end))])]);
    	}

    	if ($missing_father_name) {
            $this->deleteFile($uuid);
    		throw ValidationException::withMessages(['message' => trans('student.missing_father_name', ['name' => implode(',', array_unique($missing_father_name))])]);
    	}

    	if ($missing_father_contact_number) {
            $this->deleteFile($uuid);
    		throw ValidationException::withMessages(['message' => trans('student.missing_father_contact_number', ['name' => implode(',', array_unique($missing_father_contact_number))])]);
    	}

    	if ($duplicate_admission_numbers) {
            $this->deleteFile($uuid);
    		throw ValidationException::withMessages(['message' => trans('student.duplicate_admission_numbers', ['number' => implode(',', array_unique($duplicate_admission_numbers))])]);
    	}

        activity()->disableLogging();

        beginTransaction();

        foreach ($items as $index => $item) {

            if ($index == 0)
                continue;

            $first_name                   = gv($item, array_search('first_name', $columns));
            $middle_name                  = gv($item, array_search('middle_name', $columns));
            $last_name                    = gv($item, array_search('last_name', $columns));
            $contact_number               = gv($item, array_search('contact_number', $columns));
            $course                       = gv($item, array_search('course', $columns));
            $batch                        = gv($item, array_search('batch', $columns));
            $date_of_birth                = gv($item, array_search('date_of_birth', $columns));
            $date_of_admission            = gv($item, array_search('date_of_admission', $columns));
            $father_name                  = gv($item, array_search('father_name', $columns));
            $mother_name                  = gv($item, array_search('mother_name', $columns));
            $father_contact_number        = gv($item, array_search('father_contact_number', $columns));
            $admission_number_prefix      = gv($item, array_search('admission_number_prefix', $columns));
            $admission_number             = gv($item, array_search('admission_number', $columns));
            $gender                       = gv($item, array_search('gender', $columns));
            $nationality                  = gv($item, array_search('nationality', $columns));
            $blood_group                  = gv($item, array_search('blood_group', $columns));
            $religion                     = gv($item, array_search('religion', $columns));
            $category                     = gv($item, array_search('category', $columns));
            $caste                        = gv($item, array_search('caste', $columns));
            $unique_identification_number = gv($item, array_search('unique_identification_number', $columns));
            $emergency_contact_name       = gv($item, array_search('emergency_contact_name', $columns));
            $emergency_contact_number     = gv($item, array_search('emergency_contact_number', $columns));
            $present_address_line_1       = gv($item, array_search('present_address_line_1', $columns));
            $present_address_line_2       = gv($item, array_search('present_address_line_2', $columns));
            $present_city                 = gv($item, array_search('present_city', $columns));
            $present_state                = gv($item, array_search('present_state', $columns));
            $present_zipcode              = gv($item, array_search('present_zipcode', $columns));
            $present_country              = gv($item, array_search('present_country', $columns));
            $fee_concession               = gv($item, array_search('fee_concession', $columns));
            $transport_circle             = gv($item, array_search('transport_circle', $columns));

            $course = $courses->firstWhere('name', $course);

            $batches = $course->batches;

            $batch = $batches->where('name', $batch)->first();

            $fee_allocation = $fee_allocations->filter(function($fee_allocation) use ($course, $batch) {
                    return $fee_allocation->batch_id == $batch->id || $fee_allocation->course_id == $course->id;
                })->first();

            $existing_parent = $student_parents->where('father_name', $father_name)->where('father_contact_number_1', $father_contact_number)->first();

            if ($existing_parent) {
                $parent = $existing_parent;
                $parent_id = $parent['id'];
            } else {
                $parent = $this->parent->forceCreate([
                    'father_name'             => $father_name,
                    'father_contact_number_1' => $father_contact_number,
                    'mother_name'             => $mother_name
                ]);

                $student_parents->push([
                    'id'                      => $parent->id,
                    'father_name'             => $father_name,
                    'father_contact_number_1' => $father_contact_number
                ]);

                $parent_id = $parent->id;
            }

            $student = $this->student->forceCreate([
                'uuid'                         => Str::uuid(),
                'first_name'                   => $first_name,
                'student_parent_id'            => $parent_id,
                'middle_name'                  => $middle_name,
                'last_name'                    => $last_name,
                'date_of_birth'                => toDate($date_of_birth),
                'contact_number'               => $contact_number,
                'gender'                       => $gender,
                'nationality'                  => $nationality,
                'blood_group_id'               => optional($blood_groups->firstWhere('name', $blood_group))->id,
                'religion_id'                  => optional($religions->firstWhere('name', $religion))->id,
                'category_id'                  => optional($categories->firstWhere('name', $category))->id,
                'caste_id'                     => optional($castes->firstWhere('name', $caste))->id,
                'unique_identification_number' => $unique_identification_number,
                'emergency_contact_name'       => $emergency_contact_name,
                'emergency_contact_number'     => $emergency_contact_number,
                'present_address_line_1'       => $present_address_line_1,
                'present_address_line_2'       => $present_address_line_2,
                'present_city'                 => $present_city,
                'present_state'                => $present_state,
                'present_zipcode'              => $present_zipcode,
                'present_country'              => $present_country
            ]);

            $registration = $this->registration->forceCreate([
                'student_id' => $student->id,
                'date_of_registration' => toDate($date_of_admission),
                'status' => 'allotted'
            ]);

            $admission = $this->admission->forceCreate([
                'prefix' => $admission_number_prefix,
                'number' => $admission_number,
                'registration_id' => $registration->id,
                'date_of_admission' => toDate($date_of_admission)
            ]);

            $student_record = $this->student_record->forceCreate([
                'admission_id'        => $admission->id,
                'academic_session_id' => config('config.default_academic_session.id'),
                'batch_id'            => $batch->id,
                'fee_allocation_id'   => optional($fee_allocation)->id,
                'date_of_entry'       => toDate($date_of_admission),
                'student_id'          => $student->id
            ]);

            if ($fee_allocation) {
                $installments = array();
                foreach ($fee_allocation->feeAllocationGroups as $fee_allocation_group) {
                    foreach ($fee_allocation_group->feeInstallments as $fee_installment) {
                        $installments[] = array(
                            'student_record_id'  => $student_record->id,
                            'fee_installment_id' => $fee_installment->id,
                            'transport_circle_id' => optional($transport_circles->firstWhere('name',$transport_circle))->id,
                            'fee_concession_id' => optional($fee_concessions->firstWhere('name',$fee_concession))->id,
                            'status' => 'unpaid'
                        );
                    }
                }
                $this->student_fee_record->insert($installments); 
            }

        }

        commitTransaction();
        $this->deleteFile($uuid);
        activity()->enableLogging();
        activity('import')->log('student');
    }
}