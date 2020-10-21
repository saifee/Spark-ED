<?php
/**
 * Created by PhpStorm.
 * User: Raz
 * Date: 10/9/2018
 * Time: 10:03 PM
 */

namespace App\Traits\Amsl;


use App\Models\Amsl\Liability;
use Illuminate\Support\Facades\DB;

trait LedgerLiabilityTrait
{

    public function getLiabilitiesApLedgerData(){

        $calculationQuery=$this->getCalculationQuery($this->liabilitiesApData());
        $previousBalance=0;
        foreach($calculationQuery as $item){
            if($item->payment_type=='aPexpenseAr'){
                $previousBalance+=$item->amount;
            }else{
                $previousBalance-=$item->amount;
            }
        }
        $query= $this->makePaginate($this->liabilitiesApData());
        $custom = collect(['previous_balance' => $previousBalance]);
        $query = $custom->merge($query);
        return $query;
    }

    public function getLognTermLiaLedgerData(){

        $calculationQuery=$this->getCalculationQuery($this->longLiabilitiesData());
        $previousBalance=0;
        foreach($calculationQuery as $item){
            if($item->payment_type=='Payment'){
                $previousBalance-=$item->amount;

            }else{
                $previousBalance+=$item->amount;
            }
        }
        $query= $this->makePaginate($this->longLiabilitiesData());
        $custom = collect(['previous_balance' => $previousBalance]);
        $query = $custom->merge($query);
        return $query;
    }



    public function longLiabilitiesData(){

        $query = Liability::where('accountable_id',request()->input('id'))->where('accountable_type','=','App\Models\Amsl\Account')
            ->select('id', 'accountable_id as account_id', 'ref', 'amount', 'payment_type','transaction_type',
                'description','liability_date as date',
                DB::raw("'long-term-liabilities' AS type"))
            ->orderBy('date', 'DESC');

        $query=$this->dateSearch('liability_date',$query,request());

        return $query;

    }

    public function liabilitiesApData(){
        $liability = DB::table('liabilities')
            ->select('accountable_id','amount','transaction_type' ,'payment_type','liability_date as date','description','ref',DB::raw("'liabilityAp' AS type"))
            ->where('accountable_type','App\Models\Amsl\Account')
            ->where('accountable_id',request()->input('id'));

        $liability=$this->dateSearch('liability_date',$liability,request());

        $asset = DB::table('assets')
            ->select('account_id','amount','transaction_type' ,'payment_type','asset_date as date','description','ref',DB::raw("'liabilityAp_asset' AS type"))
            ->where('liability_id',request()->input('id'));

        $asset=$this->dateSearch('asset_date',$asset,request());

        $query = DB::table('expenses')
            ->select('account_id','amount',DB::raw("'Payment' AS transaction_type"),'payment_type','expense_date as date','description','ref',DB::raw("'aPexpenseAr' AS type"))
            ->where('modelable_id',request()->input('id'))
            ->where('modelable_type','=','App\Models\Amsl\Account')
            ->union($liability)
            ->union($asset)
            ->orderBy('date','DESC');

        $query=$this->dateSearch('expense_date',$query,request());
        return $query;
    }
}
