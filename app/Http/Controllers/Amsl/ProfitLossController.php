<?php

namespace App\Http\Controllers\Amsl;

use App\Models\Amsl\Expense;
use App\Traits\Amsl\ExpenseTrait;
use App\Traits\Amsl\IncomeTrait;
use App\Models\Amsl\Income;
use Illuminate\Support\Facades\DB;

class ProfitLossController extends Controller
{
    use IncomeTrait; use ExpenseTrait;
    public function getProfitLoss(){

        $incomes= $this->getIncome();
        $expenses= $this->getExpense();

        $vatProvide = Expense::whereBetween('expense_date',[$this->getFormDateAndToDate()['fromDate'],
                      $this->getFormDateAndToDate()['toDate']])->sum('tax_amount');

        $vatGet = Income::whereBetween('income_date',[$this->getFormDateAndToDate()['fromDate'],                            $this->getFormDateAndToDate()['toDate']])->sum('tax_amount');

        $incomeVat=$this->getIncomeVat();
        $expenseAmount=$this->getExpenseVat();


        return response()->json([
            'incomes'=>$incomes,
            'expenses'=>$expenses,
            'tax'=>($vatGet-$vatProvide)+($incomeVat-$expenseAmount)

        ]);
    }
}
