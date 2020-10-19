<?php

namespace App\Models\Amsl;

use Illuminate\Database\Eloquent\Model;

class Ownerequity extends Model
{
    protected $table='amsl_ownerequities';
    protected $guarded=['id','liability','account_payable'];


    public function accountPayable(){
        return $this->belongsTo(Account::class,'liability_id','id');
    }
}
