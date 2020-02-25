<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class StudentBehaviourPoint extends Model
{
    use LogsActivity;

    protected $fillable = [
                            'student_record_id',
                            'total',
                            'options'
                        ];
    protected $casts = ['options' => 'array',];
    protected $primaryKey = 'id';
    protected $table = 'student_behaviour_points';
    protected static $logName = 'student_behaviour_point';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $ignoreChangedAttributes = ['updated_at'];

    public function getOption(string $option)
    {
        return array_get($this->options, $option);
    }

    public function scopeFilterById($q, $id)
    {
        if (! $id) {
            return $q;
        }

        return $q->where('id', '=', $id);
    }

    public function scopeFilterByStudentRecordId($q, $student_record_id)
    {
        if (! $student_record_id) {
            return $q;
        }

        return $q->where('student_record_id', '=', $student_record_id);
    }
}
