<?php
/**
 * Created by PhpStorm.
 * User: Raz
 * Date: 10/8/2018
 * Time: 8:14 PM
 */

namespace App\Traits\Amsl;


use Illuminate\Support\Facades\DB;

trait ExpenseTrait
{
    public function getExpense(){
        $fromDate=$this->getFormDateAndToDate()['fromDate'];
        $toDate=$this->getFormDateAndToDate()['toDate'];
        $data= DB::table('amsl_accounts as accounts')
            ->select('accounts.id as aid', 'accounts.name',DB::raw("(SELECT SUM(CASE  WHEN expenses.payment_type ='Prepaid Expense' THEN -expenses.amount ELSE expenses.amount END) from amsl_expenses where account_id=aid and expense_date between '$fromDate' and '$toDate') as amount"),DB::raw("(SELECT sum(amount) from amsl_assets as assets where expense_id = aid and asset_date between '$fromDate' and '$toDate') as prepaid_amount"))
            ->leftJoin('amsl_expenses as expenses','accounts.id','=','expenses.account_id')
            ->where('accounts.account_type','=','Expense')
            ->groupBy('accounts.id');
        return $data->get();
    }


    public function getExpenseVat(){
        $data= DB::table('amsl_expenses as expenses')
            ->leftJoin('amsl_accounts as accounts','accounts.id','=','expenses.account_id')
            ->where('accounts.name','LIKE','%value%');
        $data=$this->dateSearch('expense_date',$data,request())->sum('amount');
        return $data;
    }


}



