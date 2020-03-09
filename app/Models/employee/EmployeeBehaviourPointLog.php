<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class EmployeeBehaviourPointLog extends Model
{
    protected $fillable = [
                            'employee_skill_id',
                            'employee_term_id',
                            'points',
                            'options'
                        ];
    protected $casts = ['options' => 'array',];
    protected $primaryKey = 'id';
    protected $table = 'employee_behaviour_point_logs';

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

    public function scopeFilterByEmployeeTermId($q, $employee_term_id)
    {
        if (! $employee_term_id) {
            return $q;
        }

        return $q->where('employee_term_id', '=', $employee_term_id);
    }
}
