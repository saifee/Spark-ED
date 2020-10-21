<?php
/**
 * Created by PhpStorm.
 * User: Raz
 * Date: 10/9/2018
 * Time: 10:28 PM
 */

namespace App\Traits\Amsl;


use Illuminate\Support\Facades\DB;

trait DayBookTrait
{
    public function getDayBookExpense()
    {
        $data = DB::table('amsl_expenses')->where('payment_type', request()->input('type'))->select('expense_date as date', 'ref', 'description', 'amount', DB::raw("'expense' AS type"), DB::raw("'expense' AS transaction_type"));
        return $data;
    }



    public function getDayBookAsset()
    {
        $data = DB::table('amsl_assets as assets')->where('payment_type', request()->input('type'))->select('asset_date as date', 'ref', 'description', 'amount', DB::raw("'asset' AS type"), 'transaction_type');
        return $data;
    }



    public function getDayBookLiability()
    {
        $data = DB::table('amsl_liabilities')->where('payment_type', request()->input('type'))->select('liability_date as date', 'ref', 'description', 'amount', DB::raw("'liability' AS type"), 'transaction_type');
        return $data;
    }



    public function getDayBookEquity(){
        $data=DB::table('amsl_ownerequities')->where('payment_type',request()->input('type'))->select('equity_date as date','ref','description','amount',DB::raw("'equity' AS type"),'transaction_type');
        return $data;
    }

    public function getDayBookInitialAmount(){
        $data = DB::table('amsl_assets as assets')->leftJoin('amsl_accounts as accounts','assets.account_id','=','accounts.id')->where('accounts.name', ucfirst(request()->input('type')))->select('assets.asset_date as date', 'assets.ref', 'assets.description', 'assets.amount', DB::raw("'asset' AS type"), 'assets.transaction_type');
        return $data;
    }
}
