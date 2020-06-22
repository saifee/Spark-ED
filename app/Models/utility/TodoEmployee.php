<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Model;

class TodoEmployee extends Model
{

    protected $fillable = [
                            'todo_id',
                            'assigned_by',
                            'user_id',
                            'status',
                            'date',
                            'completed_at',
                        ];

    public function todo()
    {
        return $this->belongsTo(Todo::class);
    }

    public function assigned_by()
    {
        return $this->belongsTo('App\User');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function todo_employee_todo_feedback()
    {
        return $this->hasMany(TodoEmployeeTodoFeedback::class);
    }

    public function todo_feedback()
    {
        return $this->belongsToMany(TodoFeedback::class);
    }

    public function scopeFilterByTitleOrDescription($q, $keyword = null)
    {
        if (! $keyword) {
            return $q;
        }

        return $q->where('title', 'like', '%'.$keyword.'%')->orWhere('description', 'like', '%'.$keyword.'%');
    }

    public function scopeFilterCompletedTodo($q, $status = null)
    {
        if (! $status) {
            return $q;
        }

        return $q->whereStatus(1);
    }

    public function scopeFilterByUserId($q, $user_id = null)
    {
        if (! $user_id) {
            return $q;
        }

        return $q->whereUserId($user_id);
    }
}
