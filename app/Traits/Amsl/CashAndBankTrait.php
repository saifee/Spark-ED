<?php
/**
 * Created by PhpStorm.
 * User: Raz
 * Date: 10/8/2018
 * Time: 8:10 PM
 */

namespace App\Traits\Amsl;


use Illuminate\Support\Facades\DB;

trait CashAndBankTrait
{


    public function getCash(){


        $data=DB::table('amsl_accounts as accounts')
            ->select(DB::raw("(SELECT SUM(CASE  WHEN transaction_type ='Sold' OR transaction_type ='Receive' THEN amount ELSE -amount END) from amsl_assets as assets where payment_type='Cash' and asset_date between '$this->fromDate' and '$this->toDate') as assetCashAmount"),
                DB::raw("(SELECT SUM(assets.amount) from amsl_assets as assets LEFT JOIN amsl_accounts as accounts on accounts.id = assets.account_id where accounts.name='Cash' or accounts.name='Cash' and asset_date between '$this->fromDate' and '$this->toDate') as initialCashAmount"),
                DB::raw("(SELECT SUM(amount) from amsl_expenses where payment_type='Cash' and expense_date between '$this->fromDate' and '$this->toDate') as expenseAmount"),
                DB::raw("(SELECT SUM(amount) from amsl_incomes where payment_type='Cash' and income_date between '$this->fromDate' and '$this->toDate') as incomeAmount"),
                DB::raw("(SELECT SUM(CASE  WHEN transaction_type ='Payment' THEN -amount ELSE amount END) from amsl_liabilities where payment_type='Cash' and liability_date between '$this->fromDate' and '$this->toDate') as liabilityAmount")
                , DB::raw("(SELECT SUM(CASE  WHEN transaction_type ='Payment' THEN -amount ELSE amount END) from amsl_ownerequities where payment_type='Cash' and equity_date between '$this->fromDate' and '$this->toDate') as equityAmount")
            );
        return $data->first();
    }




    public function getBank(){
        $data= DB::table('amsl_accounts as accounts')
            ->select(DB::raw("(SELECT SUM(CASE  WHEN transaction_type ='Sold' OR transaction_type ='Receive' THEN amount ELSE -amount END) from amsl_assets as assets where payment_type='Bank' and asset_date between '$this->fromDate' and '$this->toDate') as assetBankAmount"),
                DB::raw("(SELECT SUM(assets.amount) from amsl_assets as assets LEFT JOIN amsl_accounts as accounts on accounts.id = assets.account_id where accounts.name='Bank' or accounts.name='bank' and asset_date between '$this->fromDate' and '$this->toDate') as initialBankAmount"),
                DB::raw("(SELECT SUM(amount) from amsl_expenses where payment_type='Bank' and expense_date between '$this->fromDate' and '$this->toDate') as expenseAmount"),
                DB::raw("(SELECT SUM(amount) from amsl_incomes where payment_type='Bank' and income_date between '$this->fromDate' and '$this->toDate') as incomeAmount"), DB::raw("(SELECT SUM(CASE  WHEN transaction_type ='Payment' THEN -amount ELSE amount END) from amsl_liabilities where payment_type='Bank' and liability_date between '$this->fromDate' and '$this->toDate') as liabilityAmount")
                , DB::raw("(SELECT SUM(CASE  WHEN transaction_type ='Payment' THEN -amount ELSE amount END) from amsl_ownerequities where payment_type='Bank' and equity_date between '$this->fromDate' and '$this->toDate') as equityAmount")
            );

        return $data->first();
    }

}
