<?php

namespace App\Models\Behaviour;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Story extends Model
{
    use LogsActivity;

    protected $fillable = [
                            'uuid',
                            'academic_session_id',
                            'batch_id',
                            'type',
                            'content',
                            'attachment',
                            'likes_count',
                            'comments_count',
                            'user_id',
                            'options',
                        ];
    protected $casts = ['options' => 'array'];
    protected static $logName = 'story';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $ignoreChangedAttributes = ['updated_at'];

    public function acadmicSession()
    {
        return $this->belongsTo('App\Models\Academic\AcademicSession');
    }

    public function batch()
    {
        return $this->belongsTo('App\Models\Academic\Batch');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Behaviour\Story\Comment');
    }
    
    public function likes()
    {
        return $this->hasMany('App\Models\Behaviour\Story\Like');
    }
    
    public function getOption(string $option)
    {
        return array_get($this->options, $option);
    }

    public function scopeInfo($q)
    {
        return $q->with(['batch:id,course_id,name', 'batch.course:id,name', 'user:id', 'user.employee:user_id,first_name,middle_name,last_name', 'user.student:user_id,first_name,middle_name,last_name', 'user.parent:user_id,first_guardian_name']);
    }
    
    public function scopeFilterByUuid($q, $uuid)
    {
        if (! $uuid) {
            return $q;
        }

        return $q->where('uuid', '=', $uuid);
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

    public function scopeFilterByUserId($q, $user_id)
    {
        if (! $user_id) {
            return $q;
        }

        return $q->where('user_id', '=', $user_id);
    }
}
