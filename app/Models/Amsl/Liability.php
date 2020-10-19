<?php

namespace App\Models\Amsl;

use Illuminate\Database\Eloquent\Model;

class Liability extends Model
{
    protected $table='amsl_liabilities';
    protected $guarded=['id','asset','liability','accountable','account_receivable'];

    public function accountReceivable(){
        return $this->belongsTo(Account::class,'asset_id','id');
    }
    public function accountable(){
        return $this->morphTo();
    }

}
