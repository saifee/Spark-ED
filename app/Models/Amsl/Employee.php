<?php

namespace App\Models\Amsl;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table='amsl_employees';
    protected $guarded=['id'];

    public function expenses(){
        return $this->morphMany(Expense::class, 'modelable');
    }
    public function liabilities(){
        return $this->morphMany(Liability::class, 'accountable');
    }
}
