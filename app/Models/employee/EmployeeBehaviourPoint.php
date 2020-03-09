<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class EmployeeBehaviourPoint extends Model
{
    use LogsActivity;

    protected $fillable = [
                            'employee_term_id',
                            'total',
                            'options'
                        ];
    protected $casts = ['options' => 'array',];
    protected $primaryKey = 'id';
    protected $table = 'employee_behaviour_points';
    protected static $logName = 'employee_behaviour_point';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $ignoreChangedAttributes = ['updated_at'];

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
