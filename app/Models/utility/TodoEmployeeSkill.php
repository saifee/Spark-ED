<?php

namespace App\Models\Utility;

use Illuminate\Database\Eloquent\Model;

class TodoEmployeeSkill extends Model
{

    protected $fillable = [
                            'todo_id',
                            'employee_skill_id',
                            'percentage',
                        ];

    public function todo()
    {
        return $this->belongsTo(Todo::class);
    }
}
