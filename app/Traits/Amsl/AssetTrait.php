<?php
/**
 * Created by PhpStorm.
 * User: Raz
 * Date: 10/8/2018
 * Time: 8:01 PM
 */

namespace App\Traits\Amsl;


use Illuminate\Support\Facades\DB;

trait AssetTrait
{
    public function getFixedAsset(){
        $data=DB::table('amsl_accounts')
            ->leftJoin('assets', 'accounts.id', '=', 'assets.account_id')
            ->select('accounts.id as aid', 'accounts.name', DB::raw("(SELECT SUM(CASE  WHEN assets.transaction_type ='Sold' THEN -assets.amount ELSE assets.amount END ) from assets LEFT JOIN accounts on accounts.id = assets.account_id WHERE  accounts.account_type='Fixed Asset' and assets.account_id=aid and asset_date between '$this->fromDate' and '$this->toDate') as assetAmount"), DB::raw("(SELECT sum(amount) FROM expenses where asset_id= aid and expense_date between '$this->fromDate' and '$this->toDate') as expenseAmount"))
            ->where('accounts.account_type', '=', 'Fixed Asset')
            ->groupBy('accounts.id');
        return $data->get();
    }



    public function getCurrentAsset(){


        $data=DB::table('amsl_accounts')
            ->leftJoin('assets', 'assets.account_id', '=', 'accounts.id')
            ->select('accounts.name', 'accounts.id as aid', 'assets.transaction_type', DB::raw("(SELECT SUM(CASE  WHEN assets.transaction_type ='Adjust' THEN -assets.amount ELSE assets.amount END)from assets LEFT JOIN accounts on accounts.id = assets.account_id WHERE  accounts.account_type='Current Asset' and accounts.name !='Cash' and accounts.name!='Bank' and  assets.account_id = aid and asset_date between '$this->fromDate' and '$this->toDate')as assetAmount"), DB::raw("(SELECT sum(amount) FROM expenses WHERE asset_id=aid and expense_date between '$this->fromDate' and '$this->toDate')as expenseAmount"))
            ->where('accounts.account_type', 'Current Asset')
            ->where('accounts.name','!=','Cash')
            ->where('accounts.name','!=','Bank')
            ->groupBy('accounts.id');
        return $data->get();
    }



    public function getCurrentAssetAr(){


        $data=DB::table('amsl_accounts')
            ->leftJoin('assets', 'accounts.id', '=', 'assets.account_id')
            ->select('accounts.id as aid', 'accounts.name', DB::raw("(SELECT SUM(CASE  WHEN assets.transaction_type ='Receive' THEN -assets.amount ELSE assets.amount END) from assets where assets.account_id=aid and asset_date between '$this->fromDate' and '$this->toDate' )as assetAmount"), DB::raw("(SELECT sum(amount) FROM  incomes WHERE asset_id = aid and income_date between '$this->fromDate' and '$this->toDate')as incomeAmount"),
                DB::raw("(SELECT sum(amount) FROM  liabilities WHERE asset_id = aid and liability_date between '$this->fromDate' and '$this->toDate')as liabilitiesAmount")

            )
            ->where('accounts.account_type', '=', 'Current Asset-AR')
            ->groupBy('accounts.id');
        return $data->get();
    }

}
