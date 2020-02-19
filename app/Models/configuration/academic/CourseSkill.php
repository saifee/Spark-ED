<?php

namespace App\Models\Configuration\Academic;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class CourseSkill extends Model
{
    use LogsActivity;

    protected $fillable = [
                            'batch_id',
                            'position',
                            'name',
                            'options',
                            'academic_session_id',
                            'positive',
                            'points',
                            'course_skill_icon_id',
                        ];
    protected $casts = ['options' => 'array'];
    protected static $logName = 'course_skill';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $ignoreChangedAttributes = ['updated_at'];

    public function acadmicSession()
    {
        return $this->belongsTo('App\Models\Academic\AcademicSession');
    }

    public function course_skill_icon()
    {
        return $this->belongsTo('App\Models\Configuration\Academic\CourseSkillIcon');
    }

    public function batch()
    {
        return $this->belongsTo('App\Models\Academic\Batch');
    }

    public function getOption(string $option)
    {
        return array_get($this->options, $option);
    }

    public function scopeInfo($q)
    {
        return $q->with(['batch.course', 'course_skill_icon']);
    }

    public function scopeFilterById($q, $id)
    {
        if (! $id) {
            return $q;
        }

        return $q->where('id', '=', $id);
    }

    public function scopeFilterByAcademicSessionId($q, $academic_session_id)
    {
        if (! $academic_session_id) {
            return $q;
        }

        return $q->where('academic_session_id', '=', $academic_session_id);
    }

    public function scopeFilterBySession($q, $session_id = null)
    {
        return $q->where('academic_session_id', '=', $session_id ? : config('config.default_academic_session.id'));
    }

    public function scopeFilterByName($q, $name, $s = 0)
    {
        if (! $name) {
            return $q;
        }

        return ($s) ? $q->where('name', '=', $name) : $q->where('name', 'like', '%'.$name.'%');
    }
}
