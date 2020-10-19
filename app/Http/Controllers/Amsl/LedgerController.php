<?php

namespace App\Http\Controllers\Amsl;

use App\Models\Amsl\Account;
use App\Models\Amsl\Employee;
use App\Traits\Amsl\LedgerAssetTrait;
use App\Traits\Amsl\LedgerEmployeeTrait;
use App\Traits\Amsl\LedgerLiabilityTrait;
use App\Traits\Amsl\LedgerTrait;
use Illuminate\Http\Request;


class LedgerController extends Controller
{
    use LedgerTrait, LedgerAssetTrait, LedgerLiabilityTrait, LedgerEmployeeTrait;

    public function getLedgerData(Request $request)
    {
        if ($request->input('type') == "Income") {

            return $this->getIncomeLedgerData();

        } elseif ($request->input('type') == "Expense") {
            return $this->getExpneseLedgerData();

        } elseif ($request->input('type') == "Current Asset") {

            return $this->getCurrentAssetLedgerData();

        } elseif ($request->input('type') == 'Current Asset-AR') {

            return $this->getCurrentAssetArLedgerData();


        } elseif ($request->input('type') == 'Liabilities-AP') {

            return $this->getLiabilitiesApLedgerData();
        } elseif ($request->input('type') == 'Employee') {

            return $this->getEmployeeLedgerData();
        }elseif ($request->input('type') == 'income-vat') {

            return $this->getReceivedVat();

        }elseif ($request->input('type') == 'expense-vat') {

            return $this->getPaidVat();
        }elseif ($request->input('type') == 'Long-term Liabilities') {

            return $this->getLognTermLiaLedgerData();

        }elseif ($request->input('type') == 'Fixed Asset') {

            return $this->getFixedAssetLedgerData();
        }



    }


    public function getAccountReceivable()
    {
        $query = Account::whereAccountType('Current Asset-AR');
        return $this->getListForUI($query, request());
    }


    public function getAccountPayable()
    {
        $query = Account::whereAccountType('Liabilities-AP');
        return $this->getListForUI($query, request());
    }

    public function getEmployeeList()
    {
        $query = Employee::select();
        return $this->getListForUI($query, request());
    }

    public function getAssetList()
    {
        $query = Account::whereAccountType('Fixed Asset')->select();
        return $this->getListForUI($query, request());
    }

    public function getLiabilityList()
    {
        $query = Account::whereAccountType('Long-term Liabilities')->select();
        return $this->getListForUI($query, request());
    }


}
