<?php

namespace App\Models\Amsl;

use Illuminate\Database\Eloquent\Model;

class EmployeeHistory extends Model
{
    protected $table='amsl_employee_histories';
    protected $guarded=['id','employee'];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
