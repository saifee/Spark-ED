<?php
/**
 * Created by PhpStorm.
 * User: Raz
 * Date: 10/8/2018
 * Time: 8:12 PM
 */

namespace App\Traits\Amsl;


use Illuminate\Support\Facades\DB;

trait IncomeTrait
{
    public function getIncome(){
        $data=DB::table('accounts')
            ->leftJoin('incomes','incomes.account_id','=','accounts.id')
            ->select('accounts.name','accounts.id',DB::raw("SUM(incomes.amount) as amount"))
            ->where('accounts.account_type','Income')
            ->groupBy('incomes.account_id');
        $data=$this->dateSearch('income_date',$data,request());
        return $data->get();
    }

    public function getIncomeVat(){
       $data=DB::table('incomes')
            ->leftJoin('accounts','accounts.id','=','incomes.account_id')
            ->where('accounts.name','LIKE','%value%');
        $data=$this->dateSearch('income_date',$data,request())->sum('amount');
       return $data;
    }
}
