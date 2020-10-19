<?php

namespace App\Http\Controllers\Amsl;

use App\Traits\Amsl\CashAndBankTrait;
use App\Traits\Amsl\CashFlowAssetLiabilityTrait;
use App\Traits\Amsl\CashFlowTrait;
use Illuminate\Http\Request;

class CashFlowController extends Controller
{
    use CashFlowTrait,CashFlowAssetLiabilityTrait,CashAndBankTrait;
    public  $fromDate;
    public $toDate;

    public function __construct()
    {
        $this->setVariable();
    }

    public function getCashFlow(){

        $incomes= $this->getCashAndBankIncome();
        $expenses= $this->getCashAndBankExpense();
        $asset= $this->getCashAndBankAsset();
        $liability= $this->getCashAndBankLiability();
        $liabilityEm= $this->getCashAndBankLiabilityEm();
        $equity= $this->getCashAndBankEquity();
        $cash= $this->getAccountNameByCash();
        $bank= $this->getAccountNameByBank();



        return response()->json([
            'incomes'=>$incomes,
            'expenses'=>$expenses,
            'asset'=>$asset,
            'liability'=>$liability,
            'liabilityEm'=>$liabilityEm,
            'equity'=>$equity,
            'cash'=>$cash,
            'bank'=>$bank,

        ]);
    }

    public function getCashFlowPreviousBalance(){

        $cash=$this->getCash();
        $bank=$this->getBank();
        return response()->json([
            'previousCashBalance'=>$cash,
            'previousBankBalance'=>$bank,

        ]);
    }
    public function setVariable(){
        $this->fromDate = $this->getFormDateAndToDate()['fromDate'];
        $this->toDate = $this->getFormDateAndToDate()['toDate'];
    }
}
