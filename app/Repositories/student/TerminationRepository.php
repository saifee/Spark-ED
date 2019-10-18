<?php
namespace App\Repositories\Student;

use Illuminate\Support\Str;
use App\Models\Student\StudentRecord;
use App\Models\Student\TransferCertificate;
use Illuminate\Validation\ValidationException;
use App\Repositories\Configuration\Academic\CourseGroupRepository;

class TerminationRepository
{
    protected $student_record;
    protected $course_group;
    protected $transfer_certificate;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(
        StudentRecord $student_record,
        CourseGroupRepository $course_group,
        TransferCertificate $transfer_certificate
    ) {
        $this->student_record = $student_record;
        $this->course_group = $course_group;
        $this->transfer_certificate = $transfer_certificate;
    }

    /**
     * Get terminated student's filtered data
     *
     * @param array $params
     * @return StudentRecord
     */
    public function getData($params)
    {
        $sort_by        = gv($params, 'sort_by', 'date_of_exit');
        $order          = gv($params, 'order', 'desc');
        $batch_id       = gv($params, 'batch_id');
        $first_name     = gv($params, 'first_name');
        $last_name      = gv($params, 'last_name');
        $father_name    = gv($params, 'father_name');
        $mother_name    = gv($params, 'mother_name');

        $date_of_exit_start_date = gv($params, 'date_of_exit_start_date');
        $date_of_exit_end_date   = gv($params, 'date_of_exit_end_date');

        $batch_id = is_array($batch_id) ? $batch_id : ($batch_id ? explode(',', $batch_id) : []);

        $query = $this->student_record->with('student', 'student.parent', 'admission', 'batch', 'batch.course')->filterBySession()->whereNotNull('date_of_exit')->filterByBatchesId($batch_id)->whereHas('student', function ($q) use ($first_name, $last_name, $father_name, $mother_name) {
            $q->filterByFirstName($first_name)->filterByLastName($last_name);

            if ($father_name || $mother_name) {
                $q->whereHas('parent', function ($q1) use ($father_name, $mother_name) {
                    $q1->filterByFatherName($father_name)->filterByMotherName($mother_name);
                });
            }
        })->dateOfExitBetween([
                'start_date' => $date_of_exit_start_date,
                'end_date' => $date_of_exit_end_date
            ]);

        return $query->orderBy($sort_by, $order);
    }

    /**
     * Paginate terminated student records using given params.
     *
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($params)
    {
        $page_length = gv($params, 'page_length', config('config.page_length'));

        return $this->getData($params)->paginate($page_length);
    }

    /**
     * Get terminated student filtered data for printing
     *
     * @param array $params
     * @return StudentRecord
     */
    public function print($params)
    {
        return $this->getData($params)->get();
    }

    /**
     * Get student record filters.
     *
     * @return Array
     */
    public function getFilters()
    {
        $batches = $this->course_group->getBatchOption();

        return compact('batches');
    }

    /**
     * Terminate a student.
     *
     * @param StudentRecord $student_record
     * @param array $params
     */
    public function terminate(StudentRecord $student_record, $params)
    {
        if ($student_record->date_of_exit) {
            throw ValidationException::withMessages(['message' => trans('general.invalid_action')]);
        }

        $date_of_termination = gv($params, 'date_of_termination');
        $termination_remarks = gv($params, 'termination_remarks');

        if ($student_record->date_of_entry >= $date_of_termination) {
            throw ValidationException::withMessages(['message' => trans('student.date_of_termination_less_than_date_of_admission')]);
        }

        if ($student_record->Student->StudentRecords->where('date_of_entry', '>', $student_record->date_of_entry)->where('id', '!=', $student_record->id)->count()) {
            throw ValidationException::withMessages(['message' => trans('student.no_termination_allowed_in_intermediate_records')]);
        }

        $student_record->date_of_exit = $date_of_termination;
        $student_record->exit_remarks = $termination_remarks;
        $student_record->save();
    }

    /**
     * Terminate a student.
     *
     * @param StudentRecord $student_record
     * @param array $params
     */
    public function transferCertificate(StudentRecord $student_record, $params)
    {
        $transfer_certificate_format = gv($params, 'transfer_certificate_format');

        $transfer_certificate_formats = gkv(gv(getVar('data'), 'transfer_certificate_formats', []), 'id');

        if (! in_array($transfer_certificate_format, $transfer_certificate_formats)) {
            throw ValidationException::withMessages(['transfer_certificate_format' => trans('general.invalid_input')]);
        }

        // $transfer_certificate_format = searchByKey(gv(getVar('data'), 'transfer_certificate_formats', []), 'id', $transfer_certificate_format);

        $date_of_application = gv($params, 'date_of_application');
        $date_of_issue = gv($params, 'date_of_issue');
        $memo = gv($params, 'memo');

        $transfer_certificate = $this->getTransferCertificate($student_record);

        if ($date_of_issue <= $student_record->date_of_joining) {
            throw ValidationException::withMessages(['message' => trans('student.date_of_issue_should_greater_than_joining_date')]);
        }

        $prefix = gv($params, 'prefix');
        $number = gv($params, 'number');

        if (! ctype_digit($number)) {
            throw ValidationException::withMessages(['message' => trans('validation.numeric', ['attribute' => trans('student.transfer_certificate_number')])]);
        }

        $number = (int) $number;

        $exists = $this->transfer_certificate->where('id', '!=', $transfer_certificate->id);

        if ($exists->filterByPrefix($prefix)->filterByNumber($number)->count()) {
            throw ValidationException::withMessages(['message' => trans('validation.unique', ['attribute' => trans('student.transfer_certificate_number')])]);
        }

        $variables = gv($params, 'variables');

        $transfer_certificate->prefix = $prefix;
        $transfer_certificate->number = $number;
        $transfer_certificate->date_of_application = $date_of_application;
        $transfer_certificate->date_of_issue = $date_of_issue;
        $transfer_certificate->format = $transfer_certificate_format;

        $options['transfer_certificate'] = $variables;
        $options['book_number'] = gv($params, 'book_number');
        $transfer_certificate->options = $options;
        $transfer_certificate->save();
    }

    private function getTransferCertificate(StudentRecord $student_record)
    {
        $transfer_certificate = $this->transfer_certificate->firstOrCreate([
            'student_record_id' => $student_record->id,
        ]);

        $transfer_certificate->uuid = $transfer_certificate->uuid ? : Str::uuid();
        $transfer_certificate->options = $transfer_certificate->options ? : [];
        $transfer_certificate->save();

        return $transfer_certificate;
    }
}
