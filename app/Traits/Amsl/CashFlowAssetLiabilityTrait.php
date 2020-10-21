<?php
/**
 * Created by PhpStorm.
 * User: Raz
 * Date: 10/24/2018
 * Time: 8:17 PM
 */

namespace App\Traits\Amsl;


use Illuminate\Support\Facades\DB;

trait CashFlowAssetLiabilityTrait
{
    public function getCashAndBankAsset(){
        $data=DB::table('amsl_accounts as accounts')
            ->leftJoin('amsl_assets as assets','assets.account_id','=','accounts.id')
            ->select('accounts.name','assets.payment_type','accounts.id',DB::raw("(SUM(CASE  WHEN (assets.transaction_type ='Payment' and accounts.account_type='Fixed Asset') or assets.transaction_type='Adjust' or  (assets.transaction_type ='Receive' and accounts.account_type='Current Asset-AR') or assets.transaction_type ='Sold' THEN -assets.amount ELSE assets.amount END)) as amount"))
            ->where('accounts.name','NOT LIKE','%cash%')
            ->where('accounts.name','NOT LIKE','%bank%')
            ->groupBy('assets.account_id')
            ->orderBy('accounts.name');

        $data->where(function($query){
            $query->where('assets.payment_type','=','Cash')
                ->orWhere('assets.payment_type','=','Bank');
        });
        $data=$this->dateSearch('asset_date',$data,request());
        return $data->get();
    }

    public function getCashAndBankLiability(){
        $data=DB::table('amsl_accounts as accounts')
            ->leftJoin('amsl_liabilities as liabilities','liabilities.accountable_id','=','accounts.id')
            ->select('accounts.name','liabilities.payment_type','accounts.id',DB::raw("(SUM(CASE  WHEN liabilities.transaction_type ='Payment' THEN liabilities.amount ELSE -liabilities.amount END)) as amount"))
            ->where('liabilities.accountable_type','=','App\Models\Amsl\Account')
            ->groupBy('accounts.id')
            ->orderBy('accounts.name');

        $data->where(function($query){
            $query->where('liabilities.payment_type','=','Cash')
                ->orWhere('liabilities.payment_type','=','Bank');
        });
        $data=$this->dateSearch('liability_date',$data,request());
        return $data->get();
    }

    public function getCashAndBankLiabilityEm(){
        $data=DB::table('amsl_employees as employees')
            ->leftJoin('amsl_liabilities as liabilities','liabilities.accountable_id','=','employees.id')
            ->select('employees.name','liabilities.payment_type','employees.id',DB::raw("(SUM(CASE  WHEN liabilities.transaction_type ='Payment' THEN liabilities.amount ELSE -liabilities.amount END)) as amount"))
            ->where('liabilities.accountable_type','=','App\Models\Amsl\Employee')
            ->groupBy('employees.id')
            ->orderBy('employees.name');

        $data->where(function($query){
            $query->where('liabilities.payment_type','=','Cash')
                ->orWhere('liabilities.payment_type','=','Bank');
        });

        $data=$this->dateSearch('liability_date',$data,request());
        return $data->get();
    }

    public function getCashAndBankEquity(){
        $data=DB::table('amsl_ownerequities as ownerequities')
            ->select('account','payment_type','id',DB::raw("(SUM(CASE  WHEN transaction_type ='Payment' THEN -amount ELSE amount END)) as amount"))
            ->groupBy('id')
            ->orderBy('account');

        $data->where(function($query){
            $query->where('payment_type','=','Cash')
                ->orWhere('payment_type','=','Bank');
        });

        $data=$this->dateSearch('equity_date',$data,request());
        return $data->get();
    }

    public function getAccountNameByCash(){
        $data=DB::table('amsl_accounts as accounts')
            ->leftJoin('amsl_assets as assets','assets.account_id','=','accounts.id')
            ->select('accounts.name','assets.payment_type','accounts.id',DB::raw("(SUM(CASE  WHEN assets.transaction_type ='Initial' THEN assets.amount ELSE -assets.amount END)) as amount"))
            ->where('accounts.name','LIKE','%cash%')
            ->groupBy('assets.account_id')
            ->orderBy('accounts.name');
        $data=$this->dateSearch('asset_date',$data,request());
        return $data->first();
    }
    public function getAccountNameByBank(){
        $data=DB::table('amsl_accounts as accounts')
            ->leftJoin('amsl_assets as assets','assets.account_id','=','accounts.id')
            ->select('accounts.name','assets.payment_type','accounts.id',DB::raw("(SUM(CASE  WHEN assets.transaction_type ='Initial' THEN assets.amount ELSE -assets.amount END)) as amount"))
            ->where('accounts.name','LIKE','%bank%')
            ->groupBy('assets.account_id')
            ->orderBy('accounts.name');
        $data=$this->dateSearch('asset_date',$data,request());
        return $data->first();
    }
}
