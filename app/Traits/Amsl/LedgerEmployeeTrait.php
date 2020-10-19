<?php
/**
 * Created by PhpStorm.
 * User: Raz
 * Date: 10/20/2018
 * Time: 11:18 PM
 */

namespace App\Traits\Amsl;


use Illuminate\Support\Facades\DB;

trait LedgerEmployeeTrait
{

    public function getEmployeeLedgerData(){

        $calculationQuery=$this->getCalculationQuery($this->employeeLedgerData());
        $previousBalance=0;
        $previousDueBalance=0;
        foreach($calculationQuery as $item){
            if($item->type=='expenseAr'){
                $previousBalance+=$item->amount;
            }else{
                $previousBalance-=$item->amount;
            }
            if($item->type=='employeeliabilityAp'){
                $previousDueBalance=$previousDueBalance-$item->amount;
            }else{
                if($item->payment_type=='Accounts Payable'){
                    $previousDueBalance=$previousDueBalance+$item->amount;
            }
            }


        }
        $query= $this->makePaginate($this->employeeLedgerData());
        $custom = collect(['previous_balance' => $previousBalance, 'previous_due_balance'=>$previousDueBalance]);
        $query = $custom->merge($query);
        return $query;
    }







    public function employeeLedgerData()
    {
        $liability = DB::table('liabilities')
            ->select('liabilities.accountable_id', 'liabilities.amount', 'liabilities.transaction_type', 'liabilities.payment_type', 'liabilities.liability_date as date', 'liabilities.description', 'liabilities.ref', DB::raw("'employeeliabilityAp' AS type"))
            ->where('liabilities.accountable_type', 'App\Employee')
            ->where('liabilities.accountable_id', request()->input('id'));
        $liability = $this->dateSearch('liability_date', $liability, request());

        $query = DB::table('expenses')
            ->select('expenses.account_id', 'expenses.amount', DB::raw("'Payment' AS transaction_type"), 'expenses.payment_type', 'expenses.expense_date as date', 'expenses.description', 'expenses.ref', DB::raw("'expenseAr' AS type"))
            ->where('expenses.employee_id', request()->input('id'))
            ->union($liability)
            ->orderBy('date', 'DESC');
        $query = $this->dateSearch('expense_date', $query, request());

        return $query;
    }
}
