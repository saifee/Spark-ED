<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Model;

class TodoTask extends Model
{

    protected $fillable = [
                            'todo_id',
                            'description',
                            'status',
                            'date',
                            'completed_at',
                        ];

    public function todo()
    {
        return $this->belongsTo(Todo::class);
    }

    public function scopeFilterByDescription($q, $keyword = null)
    {
        if (! $keyword) {
            return $q;
        }

        return $q->where('description', 'like', '%'.$keyword.'%');
    }

    public function scopeFilterCompletedTodo($q, $status = null)
    {
        if (! $status) {
            return $q;
        }

        return $q->whereStatus(1);
    }
}
