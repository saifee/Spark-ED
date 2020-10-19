<?php

namespace App\Models\Amsl;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table='amsl_expenses';
   protected $guarded=['id','employee','payable_details','modelable','asset','due_amount'];

    public function account(){
        return $this->belongsTo(Account::class);
    }

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function asset(){
        return $this->belongsTo(Account::class);
    }

    public function modelable(){
        return $this->morphTo();
    }

}
