<?php

namespace App\Models\Configuration\Behaviour;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Skill extends Model
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
                            'skill_icon_id',
                        ];
    protected $casts = ['options' => 'array'];
    protected static $logName = 'skill';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $ignoreChangedAttributes = ['updated_at'];

    public function acadmicSession()
    {
        return $this->belongsTo('App\Models\Academic\AcademicSession');
    }

    public function skill_icon()
    {
        return $this->belongsTo('App\Models\Configuration\Behaviour\SkillIcon');
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
        return $q->with(['batch.course', 'skill_icon']);
    }

    public function scopeFilterById($q, $id)
    {
        if (! $id) {
            return $q;
        }

        return $q->where('id', '=', $id);
    }

    public function scopeFilterByBatchId($q, $batch_id)
    {
        if (! $batch_id) {
            return $q;
        }

        return $q->where('batch_id', '=', $batch_id);
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
