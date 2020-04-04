<?php

namespace App\Models\Behaviour;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
                            'employee_id',
                            'student_parent_id',
                            'messages_student_id_foreign',
                            'messages_batch_id_foreign',
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
}
