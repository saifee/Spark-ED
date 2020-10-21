<?php
/**
 * Created by PhpStorm.
 * User: Raz
 * Date: 10/20/2018
 * Time: 10:52 PM
 */

namespace App\Traits\Amsl;

use App\Models\Amsl\Asset;
use App\Models\Amsl\Income;
use Illuminate\Support\Facades\DB;

trait LedgerIncomeExpense
{
    public function incomeLedgerData()
    {

        $query = Income::whereAccountId(request()->input('id'))->select('id', 'account_id', 'ref', 'amount', 'description', 'payment_type', 'income_date as date', DB::raw("'income' AS type"));
        return $query;
    }


    public function expenseLedgerData()
    {
        $asset = Asset::whereExpenseId(request()->input('id'))->select('id', 'account_id', 'ref', 'amount', 'payment_type', 'description', 'asset_date as date', DB::raw("'expense' AS type"))->where('payment_type', 'Adjust');
        $asset = $this->dateSearch('asset_date', $asset, request());
        $query = DB::table('amsl_expenses')
            ->select('id', 'account_id', 'ref', 'amount', 'payment_type', 'description', 'expense_date as date', DB::raw("'expense' AS type"))
            ->where('account_id', request()->input('id'))
            ->union($asset)
            ->orderBy('date', 'DESC');

        $query = $this->dateSearch('expense_date', $query, request());

        return $query;

    }



}
