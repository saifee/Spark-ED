<?php
/**
 * Created by PhpStorm.
 * User: Raz
 * Date: 10/9/2018
 * Time: 10:03 PM
 */

namespace App\Traits\Amsl;


use App\Models\Amsl\Asset;
use Illuminate\Support\Facades\DB;

trait LedgerAssetTrait
{
    public function getCurrentAssetLedgerData()
    {
        $calculationQuery=$this->getCalculationQuery($this->currentAssetData());
        $previousBalance=0;
        foreach($calculationQuery as $item){
            if($item->payment_type=='Adjust'){
                $previousBalance-=$item->amount;
            }else{
                $previousBalance+=$item->amount;
            }

        }
        $query= $this->makePaginate($this->currentAssetData());
        $custom = collect(['previous_balance' => $previousBalance]);
        $query = $custom->merge($query);
        return $query;
    }


    public function getCurrentAssetArLedgerData()
    {
        $calculationQuery=$this->getCalculationQuery($this->currentAssetArData());
        $previousBalance=0;
        foreach($calculationQuery as $item){
            if($item->payment_type=='assetAr'){
                $previousBalance -=$item->amount;
            }else{
                $previousBalance+=$item->amount;
            }

        }
        $query= $this->makePaginate($this->currentAssetArData());
        $custom = collect(['previous_balance' => $previousBalance]);
        $query = $custom->merge($query);
        return $query;
    }



    public function getFixedAssetLedgerData(){
        $calculationQuery=$this->getCalculationQuery($this->fixedAssetData());
        $previousBalance=0;
        foreach($calculationQuery as $item){
            if($item->transaction_type=='Purchase' || $item->transaction_type=='Intial'){
                $previousBalance+=$item->amount;

            }else{
                $previousBalance-=$item->amount;
            }

        }
        $query= $this->makePaginate($this->fixedAssetData());
        $custom = collect(['previous_balance' => $previousBalance]);
        $query = $custom->merge($query);
        return $query;
    }



    public function currentAssetData(){
        $asset = Asset::where('account_id',request()->input('id'))
            ->select('id', 'account_id', 'ref', 'amount', 'payment_type',
                'description', 'expense_id', 'asset_date as date',
                DB::raw("'prepaid expense' AS type"),
                DB::raw("'asset' AS come_from"))->where('payment_type', 'Adjust');

        $asset=$this->dateSearch('asset_date',$asset,request());

        $query = DB::table('amsl_expenses')
            ->select('id', 'account_id', 'ref', 'amount', 'payment_type',
                'description', DB::raw("'null' AS expense_id"), 'expense_date as date',
                DB::raw("'prepaid expense' AS type"),
                DB::raw("'expense' AS come_from"))
            ->where('payment_type', 'Prepaid Expense')
            ->where('asset_id', request()->input('id'))
            ->union($asset)
            ->orderBy('date', 'DESC');



        $getAsset = $asset->get();
        if($getAsset->contains('account_id')){
            $query->whereAccountId($getAsset[0]->expense_id);
        }
        $query=$this->dateSearch('expense_date',$query,request());
        return $query;
    }



    public function fixedAssetData(){
        $asset = Asset::where('account_id',request()->input('id'))
            ->select('id', 'account_id', 'ref', 'amount', 'payment_type','transaction_type',
                'description','asset_date as date',
                DB::raw("'fixed-asset' AS type"));

        $asset=$this->dateSearch('asset_date',$asset,request());

        $query = DB::table('amsl_expenses')
            ->select('id', 'account_id', 'ref', 'amount', 'payment_type',
                DB::raw("'depreciation' AS transaction_type"),
                'description','expense_date as date',
                DB::raw("'fixed-asset' AS type"))
            ->where('payment_type', 'Depreciation Fund')
            ->where('asset_id', request()->input('id'))
            ->union($asset)
            ->orderBy('date', 'DESC');

        $query=$this->dateSearch('expense_date',$query,request());
        return $query;
    }




    public function currentAssetArData(){
        $asset = DB::table('amsl_assets')
            ->select('assets.account_id', 'assets.amount',
                'assets.transaction_type', 'assets.payment_type',
                'assets.asset_date as date', 'assets.description',
                'assets.ref', DB::raw("'assetAr' AS type"))
            ->where('assets.account_id', request()->input('id'));

        $asset=$this->dateSearch('asset_date',$asset,request());


        $query = DB::table('amsl_incomes')
            ->select('incomes.account_id', 'incomes.amount',
                DB::raw("'Receive' AS transaction_type"),
                'incomes.payment_type', 'incomes.income_date as date',
                'incomes.description', 'incomes.ref',
                DB::raw("'incomeAr' AS type"))
            ->where('incomes.asset_id', request()->input('id'))
            ->union($asset)
            ->orderBy('date', 'DESC');

        $query=$this->dateSearch('income_date',$query,request());
        return $query;
    }
}
