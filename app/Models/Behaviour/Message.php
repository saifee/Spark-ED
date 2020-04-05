<?php

namespace App\Models\Behaviour;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
                            'sender_id',
                            'receiver_id',
                            'student_record_id',
                            'content',
                            'read_at',
                        ];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee\Employee');
    }

    public function student_parent()
    {
        return $this->belongsTo('App\Models\Student\StudentParent');
    }

    public function scopeInfo($q)
    {
        return $q->with([]);
    }

    public function scopeMy($q)
    {
        return $q->where(function($q) {
            $q->where('sender_id', auth()->user()->id)
                ->orWhere('receiver_id', auth()->user()->id);
        });
    }

    public function scopeToMe($q)
    {
        return $q->where('receiver_id', auth()->user()->id);
    }

    public function scopeFilterByStudentRecordID($q, $student_record_id)
    {
        if (! $student_record_id) {
            return $q;
        }

        return $q->where('student_record_id', '=', $student_record_id);
    }
}
