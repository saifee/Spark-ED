<?php

namespace App\Models\Amsl;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table='amsl_accounts';
    protected $guarded=['id'];

    public function expenses(){
        return $this->morphMany(Expense::class, 'modelable');
    }

    public function liabilities(){
        return $this->morphMany(Liability::class, 'accountable');
    }
}
