<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Model;

class TodoEmployeeTodoFeedback extends Model
{

    protected $fillable = [
                            'user_id',
                            'todo_employee_id',
                            'todo_feedback_id',
                            'comments',
                        ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function todo_employee()
    {
        return $this->belongsTo(TodoEmployee::class);
    }

    public function todo_feedback()
    {
        return $this->belongsTo(TodoFeedback::class);
    }
}
