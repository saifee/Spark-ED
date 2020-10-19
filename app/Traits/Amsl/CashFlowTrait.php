<?php
/**
 * Created by PhpStorm.
 * User: Raz
 * Date: 10/17/2018
 * Time: 12:00 AM
 */

namespace App\Traits\Amsl;


use Illuminate\Support\Facades\DB;

trait CashFlowTrait
{
    public function getCashAndBankIncome(){

        $data=DB::table('accounts')
            ->leftJoin('incomes','incomes.account_id','=','accounts.id')
            ->select('accounts.name','incomes.payment_type','accounts.id',DB::raw("SUM(incomes.amount) as amount"))
            ->where('accounts.account_type','Income')
            ->groupBy('incomes.account_id');

        $data->where(function($query){
            $query->where('incomes.payment_type','=','Cash')
                ->orWhere('incomes.payment_type','=','Bank');
        });
          $data=$this->dateSearch('incomes.income_date',$data,request());

          return $data->get();

    }

    public function getCashAndBankExpense(){

        $data= DB::table('accounts')
            ->select('accounts.id as aid','expenses.payment_type','accounts.name',DB::raw("(SUM(CASE  WHEN expenses.payment_type ='Prepaid Expense' THEN -expenses.amount ELSE expenses.amount END)) as amount"))
            ->leftJoin('expenses','accounts.id','=','expenses.account_id')
            ->where('accounts.account_type','=','Expense')
            ->groupBy('accounts.id');

        $data->where(function($query){
            $query->where('expenses.payment_type','=','Cash')
                ->orWhere('expenses.payment_type','=','Bank');
        });

            $data=$this->dateSearch('expense_date',$data,request());


        return $data->get();


    }


}
