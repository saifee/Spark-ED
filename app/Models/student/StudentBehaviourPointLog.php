<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Model;

class StudentBehaviourPointLog extends Model
{
    protected $fillable = [
                            'skill_id',
                            'employee_id',
                            'points',
                            'options'
                        ];
    protected $casts = ['options' => 'array',];
    protected $primaryKey = 'id';
    protected $table = 'student_behaviour_point_logs';

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
}
