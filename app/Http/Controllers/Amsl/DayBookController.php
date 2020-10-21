<?php

namespace App\Http\Controllers\Amsl;

use App\Traits\Amsl\DayBookTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class DayBookController extends Controller
{
    use DayBookTrait;
    public  function getDayBook( ){

        $expenses=$this->getDayBookExpense();
        $asset= $this->getDayBookAsset();
        $liablility=$this->getDayBookLiability();
        $equity=$this->getDayBookEquity();
        $initialAmount=$this->getDayBookInitialAmount();


        $query=DB::table('amsl_incomes')
            ->select('income_date as date','ref','description','amount',DB::raw("'income' AS type"),DB::raw("'income' AS transaction_type"))->where('payment_type',request()->input('type'))
            ->union($expenses)
            ->union($asset)
            ->union($liablility)
            ->union($equity)
            ->union($initialAmount)
            ->orderBy('date','DESC');

        return $this->makePaginate($query);

    }

}
