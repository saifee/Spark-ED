<?php
/**
 * Created by PhpStorm.
 * User: Raz
 * Date: 10/9/2018
 * Time: 9:52 PM
 */

namespace App\Traits\Amsl;



use Illuminate\Support\Facades\DB;

trait LedgerTrait
{
    use LedgerIncomeExpense;
    public function getIncomeLedgerData()
    {
        $calculationQuery=$this->getCalculationQuery($this->incomeLedgerData());
        $previousBalance=0;
        foreach($calculationQuery as $item){
           $previousBalance+=$item->amount;

        }
        $query= $this->makePaginate($this->incomeLedgerData());
        $custom = collect(['previous_balance' => $previousBalance]);
        $query = $custom->merge($query);
        return $query;
    }


    public function getExpneseLedgerData()
    {

            $calculationQuery=$this->getCalculationQuery($this->expenseLedgerData());
            $previousBalance=0;
        foreach($calculationQuery as $item){
            if($item->payment_type=='Adjust'){
                $previousBalance-=$item->amount;
            }else{
                $previousBalance+=$item->amount;
            }
        }
        $query= $this->makePaginate($this->expenseLedgerData());
        $custom = collect(['previous_balance' => $previousBalance]);
        $query = $custom->merge($query);
        return $query;

    }


    public function getReceivedVat()
    {
        $calculationQuery=$this->getCalculationQuery($this->receiveVat());
        $previousBalance=0;
        foreach($calculationQuery as $item){
            $previousBalance+=$item->amount;
        }
        $query= $this->makePaginate($this->receiveVat());
        $custom = collect(['previous_balance' => $previousBalance]);
        $query = $custom->merge($query);
        return $query;

    }



    public function getPaidVat()
    {
        $calculationQuery=$this->getCalculationQuery($this->paidVat());
        $previousBalance=0;
        foreach($calculationQuery as $item){
            $previousBalance+=$item->amount;
        }
        $query= $this->makePaginate($this->paidVat());
        $custom = collect(['previous_balance' => $previousBalance]);
        $query = $custom->merge($query);
        return $query;

    }



    public function receiveVat(){

        $receiveAsIncome = DB::table('amsl_accounts')
            ->leftJoin('incomes','accounts.id','=','incomes.account_id')
            ->select('incomes.account_id','incomes.amount','incomes.payment_type','incomes.income_date as date','incomes.description','incomes.ref',DB::raw("'receiveVat' AS type"))
            ->where('accounts.account_type','=','Income')
            ->where('accounts.name','LIKE','%value%');

        $receiveAsIncome = $this->dateSearch('income_date', $receiveAsIncome, request());

        $query = DB::table('amsl_incomes')
            ->select('account_id','tax_amount as amount','payment_type','income_date as date','description','ref',DB::raw("'receiveVat' AS type"))
            ->union($receiveAsIncome)
            ->where('tax_amount','!=',0)
            ->orderBy('date','DESC');
        $query=$this->dateSearch('income_date',$query,request());
        return $query;
    }


    public function paidVat(){

        $paidAsExpense = DB::table('amsl_accounts')
            ->leftJoin('expenses','accounts.id','=','expenses.account_id')
            ->select('expenses.account_id','expenses.amount','expenses.payment_type','expenses.expense_date as date','expenses.description','expenses.ref',DB::raw("'paidVat' AS type"))
            ->where('accounts.account_type','=','Expense')
            ->where('accounts.name','LIKE','%value%');

        $paidAsExpense = $this->dateSearch('expense_date', $paidAsExpense, request());
        $query = DB::table('amsl_expenses')
            ->select('account_id','tax_amount as amount','payment_type','expense_date as date','description','ref',DB::raw("'paidVat' AS type"))
            ->union($paidAsExpense)
            ->where('tax_amount','!=',0)
            ->orderBy('date','DESC');
        $query=$this->dateSearch('expense_date',$query,request());

        return $query;
    }

}
