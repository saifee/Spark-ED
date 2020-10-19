<?php
/**
 * Created by PhpStorm.
 * User: Raz
 * Date: 10/8/2018
 * Time: 8:09 PM
 */

namespace App\Traits\Amsl;


use Illuminate\Support\Facades\DB;

trait EquityTrait
{
    public function getCapital(){
        $data=DB::table('ownerequities')
            ->select('ownerequities.account', 'ownerequities.transaction_type', DB::raw("SUM(CASE  WHEN transaction_type ='Payment' THEN -amount ELSE amount END) as amount"))
            ->where('ownerequities.account', '=','Capital')
            ->groupBy('ownerequities.account');
        $data=$this->dateSearch('equity_date',$data,request());
        return $data->first();
    }




    public function getOtherCapital(){
        $data=DB::table('accounts')
            ->select(DB::raw("(SELECT SUM(CASE  WHEN transaction_type = 'Sold' THEN -amount ELSE amount END) from assets where payment_type='Owner Equity' and asset_date between '$this->fromDate' and '$this->toDate')as assetEquityAmount"), DB::raw("(Select SUM(CASE  WHEN transaction_type = 'Payment' THEN amount ELSE -amount END) from liabilities  where payment_type='Owner Equity' and liability_date between '$this->fromDate' and '$this->toDate') as liabilityAmount"));


        return $data->first();
    }





    public function getWithdraw(){
        $data=DB::table('ownerequities')
            ->select('ownerequities.account', 'ownerequities.transaction_type', DB::raw("SUM(CASE  WHEN transaction_type ='Payment' THEN amount ELSE -amount END) as amount"))
            ->where('ownerequities.account', '=','Withdraw')
            ->groupBy('ownerequities.account');
        $data=$this->dateSearch('equity_date',$data,request());
        return $data->first();
    }
}
