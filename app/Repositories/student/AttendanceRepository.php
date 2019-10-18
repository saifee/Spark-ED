<?php
namespace App\Repositories\Student;

use Illuminate\Support\Carbon;
use App\Jobs\SendCustomizedSMS;
use App\Models\Calendar\Holiday;
use App\Models\Academic\ClassTeacher;
use App\Models\Student\StudentRecord;
use App\Models\Student\StudentAttendance;
use App\Repositories\Academic\BatchRepository;
use Illuminate\Validation\ValidationException;
use App\Repositories\Academic\SubjectRepository;
use App\Repositories\Configuration\Academic\CourseGroupRepository;

class AttendanceRepository
{
    protected $student_record;
    protected $course_group;
    protected $holiday;
    protected $student_attendance;
    protected $batch;
    protected $subject;
    protected $class_teacher;

    /**
     * Instantiate a new instance.
     *
     * @return void
     */
    public function __construct(
        StudentRecord $student_record,
        CourseGroupRepository $course_group,
        Holiday $holiday,
        StudentAttendance $student_attendance,
        BatchRepository $batch,
        SubjectRepository $subject,
        ClassTeacher $class_teacher
    ) {
        $this->student_record = $student_record;
        $this->course_group = $course_group;
        $this->holiday = $holiday;
        $this->student_attendance = $student_attendance;
        $this->batch = $batch;
        $this->subject = $subject;
        $this->class_teacher = $class_teacher;
    }

    /**
     * Get attendance pre requisite.
     *
     * @return Array
     */
    public function getPreRequisite()
    {
        $batches = $this->course_group->getBatchOption();

        $holiday_lists = $this->holiday->filterBySession()->get();
        $holidays = $holiday_lists->pluck('date')->all();

        return compact('batches', 'holidays','holiday_lists');
    }

    /**
     * Fetch student record and their attenance.
     *
     * @return Array
     */
    public function fetch($params)
    {
        $batch_id = gv($params, 'batch_id');
        $date = gv($params, 'date');

        $query = $this->student_record->with('student', 'student.parent', 'admission','batch')->filterBySession()->filterByBatchId($batch_id);

        if (\Auth::user()->hasRole(config('system.default_role.student'))) {
            $query->whereHas('student', function($q) {
                $q->whereId(\Auth::user()->Student->id);
            });
        }

        if (\Auth::user()->hasRole(config('system.default_role.parent'))) {
            $query->whereHas('student', function($q) {
                $q->whereIn('id', \Auth::user()->Parent->Students->pluck('id')->all());
            });
        }

        $student_records = $query->get();

        $batch = $this->batch->findOrFail($batch_id);
        $course = $batch->Course;

        $date = ($date) ? : date('Y-m-d');
        $days = Carbon::parse($date)->daysInMonth;

        $start_date = date('Y-m', strtotime($date)).'-01';
        $end_date = date('Y-m', strtotime($date)).'-'.$days;

        $class_teachers = $this->getClassTeachers($batch_id);

        $attendances = $this->student_attendance->filterbyBatchId($batch_id)->dateOfAttendanceBetween([
            'start_date' => $start_date,
            'end_date' => $end_date
        ])->get();

        return compact('student_records', 'batch', 'attendances','course','class_teachers');
    }

    /**
     * Get class teachers for attendance
     * @param  integer $batch_id
     * @return ClassTeacher
     */
    private function getClassTeachers($batch_id)
    {
        $class_teachers = $this->class_teacher->filterByBatchId($batch_id)->orderBy('date_effective','desc')->get(['employee_id','date_effective']);

        $employee_id = optional(\Auth::user()->Employee)->id;
        foreach ($class_teachers as $class_teacher) {
            $class_teacher->is_me = ($class_teacher->employee_id == $employee_id) ? true : false;
        }

        return $class_teachers;
    }

    /**
     * Store student attendance.
     *
     * @return Array
     */
    public function store($params = array())
    {
        $batch_id = gv($params, 'batch_id');
        $date_of_attendance = gv($params, 'date_of_attendance');

        $class_teachers = $this->getClassTeachers($batch_id);

        $auth_user = \Auth::user();

        if (! $auth_user->can('mark-student-attendance') && $auth_user->can('mark-class-teacher-wise-student-attendance') && ! amIClassTeacherOnDate($class_teachers, $date_of_attendance)) {
            throw ValidationException::withMessages(['message' => trans('general.permission_denied')]);
        }

        if (! dateBetweenSession($date_of_attendance)) {
            throw ValidationException::withMessages(['message' => trans('academic.invalid_session_date_range')]);
        }

        $student_records = $this->student_record->filterBySession()->filterbyBatchId($batch_id)->where('date_of_entry','<=',$date_of_attendance)->where(function($q) use($date_of_attendance) {
            $q->where('date_of_exit',null)->orWhere(function($q1) use($date_of_attendance) {
                $q1->where('date_of_exit','!=',null)->where('date_of_exit','>=',$date_of_attendance);
            });
        })->get();

        $subject_id = gv($params, 'subject_id');

        if ($subject_id) {
            $subject = $this->subject->findOrFail($subject_id);
        }

        if (! config('config.allow_to_modify_student_attendance') && $date_of_attendance < date('Y-m-d')) {
            throw ValidationException::withMessages(['message' => trans('student.cannot_modify_attendance_of_previous_dates')]);
        }

        if (config('config.allow_to_modify_student_attendance') && $date_of_attendance < date('Y-m-d') && dateDiff(date('Y-m-d'), $date_of_attendance) > config('config.days_allowed_to_modify_student_attendance')) {
            throw ValidationException::withMessages(['message' => trans('student.can_mark_attendance_of_days', ['day' => config('config.days_allowed_to_modify_student_attendance')] )]);
        }

        if (! config('config.allow_to_mark_student_advance_attendance') && $date_of_attendance > date('Y-m-d')) {
            throw ValidationException::withMessages(['message' => trans('student.cannot_mark_attendance_of_advance_dates')]);
        }

        if (config('config.allow_to_mark_student_advance_attendance') && $date_of_attendance > date('Y-m-d') && dateDiff(date('Y-m-d'), $date_of_attendance) > config('config.days_allowed_to_mark_student_advance_attendance')) {
            throw ValidationException::withMessages(['message' => trans('student.can_mark_advance_attendance_of_days', ['day' => config('config.days_allowed_to_mark_student_advance_attendance')] )]);
        }

        $students = gv($params, 'students', []);

        if (! $students) {
            throw ValidationException::withMessages(['message' => trans('student.could_not_find')]);
        }

        $student_record_ids = $student_records->pluck('id')->all();
        foreach ($students as $student) {
            $status = gv($student, 'attendance');
            
            if (! in_array(gv($student, 'id'), $student_record_ids) && $status != 'unavailable') {
                throw ValidationException::withMessages(['message' => trans('student.invalid_student_on_date', ['date' => showDate($date_of_attendance), 'name' => gv($student, 'name').' ('.gv($student, 'roll_number').')' ] )]);
            }
        }

        $attendances = array();
        foreach ($students as $student) {
            $id     = gv($student, 'id');
            $status = gv($student, 'attendance');

            if (($status == 'absent' || $status == 'unmarked') && $status != 'unavailable') {
                $attendances[] = array('id' => $id);
            }
        }

        $attendance['data'] = $attendances;

        $student_attendance = $this->student_attendance->firstOrCreate([
            'date_of_attendance' => $date_of_attendance,
            'batch_id' => $batch_id,
            'subject_id' => $subject_id
        ]);

        $student_attendance->attendance = $attendance;
        $student_attendance->save();
    }

    /**
     * Get student absentee filters.
     *
     * @return Array
     */
    public function getAbsenteeFilters()
    {
        $batches = $this->course_group->getBatchOption();

        return compact('batches');
    }

    /**
     * Get absentee list
     * @param  array $params
     * @return StudentRecord
     */
    public function getAbsenteeData($params = array())
    {
        $date        = gv($params, 'date', date('Y-m-d'));
        $batch_id    = gv($params, 'batch_id');
        $first_name  = gv($params, 'first_name');
        $last_name   = gv($params, 'last_name');
        $father_name = gv($params, 'father_name');
        $mother_name = gv($params, 'mother_name');

        $batch_id = is_array($batch_id) ? $batch_id : ($batch_id ? explode(',', $batch_id) : $this->batch->listId());

        $student_attendances = $this->student_attendance->filterByDateOfAttendance($date)->whereIn('batch_id', $batch_id)->get();

        $student_record_ids = array();

        foreach ($student_attendances as $student_attendance) {
            foreach ($student_attendance->attendance as $attendances) {
                foreach ($attendances as $attendance) {
                    $student_record_ids[] = array_get($attendance, 'id');
                }
            }
        }

        return $this->student_record->with('student','student.parent','batch','batch.course','admission')->whereIn('id', $student_record_ids)->whereHas('student', function ($q) use ($first_name, $last_name, $father_name, $mother_name) {
            $q->filterByFirstName($first_name)->filterByLastName($last_name);

            if ($father_name || $mother_name) {
                $q->whereHas('parent', function ($q1) use ($father_name, $mother_name) {
                    $q1->filterByFatherName($father_name)->filterByMotherName($mother_name);
                });
            }

            if (\Auth::user()->hasRole(config('system.default_role.student'))) {
                $q->whereId(\Auth::user()->Student->id);
            }

            if (\Auth::user()->hasRole(config('system.default_role.parent'))) {
                $q->whereIn('id', \Auth::user()->Parent->Students->pluck('id')->all());
            }
        })->orderBy('roll_number','asc');
    }

    /**
     * Paginate all absentee students using given params.
     *
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateAbsentee($params)
    {
        $page_length = gv($params, 'page_length', config('config.page_length'));

        return $this->getAbsenteeData($params)->paginate($page_length);
    }

    /**
     * Get all filtered data for printing
     *
     * @param array $params
     * @return StudentRecord
     */
    public function printAbsentee($params)
    {
        return $this->getAbsenteeData($params)->get();
    }

    /**
     * Send absentee SMS
     *
     * @param array $params
     * @return null
     */
    public function sendSMSToAbsentee($params)
    {
        $filter          = gv($params, 'filter', []);
        $filter['ids']   = gv($params, 'ids', []);
        $sms             = gv($params, 'sms');
        $student_records = $this->getAbsenteeData($filter)->get();

        $data = array();
        foreach ($student_records as $student_record) {
            $new_sms = $sms;
            $new_sms = str_replace('#NAME#', $student_record->student->name, $new_sms);
            $new_sms = str_replace('#BATCH#', $student_record->batch->course->name.' '.$student_record->batch->name, $new_sms);
            $new_sms = str_replace('#FATHER_NAME#', $student_record->student->parent->father_name, $new_sms);
            $new_sms = str_replace('#DATE#', toDate($filter['date']), $new_sms);

            if (in_array($student_record->id, gv($params, 'ids', [])))
                $data[] = array('to' => $student_record->student->contact_number, 'sms' => $new_sms);
        }

        $collection = collect($data);

        foreach ($collection->chunk(config('config.max_sms_per_chunk')) as $chunk) {
            SendCustomizedSMS::dispatch($chunk);
        }
    }

    /**
     * Delete attendance
     *
     * @param array $params
     * @return null
     */
    public function delete($params = array())
    {
        $batch_id = gv($params, 'batch_id');
        $date_of_attendance = gv($params, 'date_of_attendance');

        $class_teachers = $this->getClassTeachers($batch_id);

        $auth_user = \Auth::user();

        if (! $auth_user->can('mark-student-attendance') && $auth_user->can('mark-class-teacher-wise-student-attendance') && ! amIClassTeacherOnDate($class_teachers, $date_of_attendance)) {
            throw ValidationException::withMessages(['message' => trans('general.permission_denied')]);
        }

        if (! dateBetweenSession($date_of_attendance)) {
            throw ValidationException::withMessages(['message' => trans('academic.invalid_session_date_range')]);
        }

        $subject_id = gv($params, 'subject_id');

        if ($subject_id) {
            $subject = $this->subject->findOrFail($subject_id);
        }

        if (! config('config.allow_to_modify_student_attendance') && $date_of_attendance < date('Y-m-d')) {
            throw ValidationException::withMessages(['message' => trans('student.cannot_modify_attendance_of_previous_dates')]);
        }

        if (config('config.allow_to_modify_student_attendance') && $date_of_attendance < date('Y-m-d') && dateDiff(date('Y-m-d'), $date_of_attendance) > config('config.days_allowed_to_modify_student_attendance')) {
            throw ValidationException::withMessages(['message' => trans('student.can_mark_attendance_of_days', ['day' => config('config.days_allowed_to_modify_student_attendance')] )]);
        }

        if (! config('config.allow_to_mark_student_advance_attendance') && $date_of_attendance > date('Y-m-d')) {
            throw ValidationException::withMessages(['message' => trans('student.cannot_mark_attendance_of_advance_dates')]);
        }

        if (config('config.allow_to_mark_student_advance_attendance') && $date_of_attendance > date('Y-m-d') && dateDiff(date('Y-m-d'), $date_of_attendance) > config('config.days_allowed_to_mark_student_advance_attendance')) {
            throw ValidationException::withMessages(['message' => trans('student.can_mark_advance_attendance_of_days', ['day' => config('config.days_allowed_to_mark_student_advance_attendance')] )]);
        }

        $student_attendance = $this->student_attendance->filterByBatchId($batch_id)->filterBySubjectId($subject_id)->filterByDateOfAttendance($date_of_attendance)->first();

        if (! $student_attendance) {
            throw ValidationException::withMessages(['message' => trans('student.could_not_find_attendance')]);
        }

        $student_attendance->delete();
    }
}
