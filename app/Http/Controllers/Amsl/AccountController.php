<?php

namespace App\Http\Controllers\Amsl;

use App\Models\Amsl\Account;
use App\Models\Amsl\Asset;
use App\Models\Amsl\Expense;
use App\Http\Requests\AmslAccountRequest;
use App\Traits\Amsl\CashAndBankTrait;
use App\Traits\Amsl\DashboardTrait;
use App\Models\Amsl\Income;
use App\Models\Amsl\Liability;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    use DashboardTrait; use CashAndBankTrait;

    public  $fromDate;
    public $toDate;

    public function __construct()
    {
        $this->setVariable();
    }

    public function index(Request $request)
    {
        $query=Account::select();
        return $this->getListForUI($query, $request);
    }

    public function getAccountList(){

        $query=$this->getAccountListData();
        return $query->get();
    }

    public function getAccountName($id){
        return Account::whereId($id)->first();
    }
    public function create()
    {

    }


    public function store(AccountRequest $request)
    {
        Account::create($request->all());
        return response()->json([
            'type'=>'success',
            'message'=>'Account has been created successfully'
        ]);

    }

    public function show(Account $employee)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        return Account::find($id);
    }

    public function update(AccountRequest $request, $id)
    {
        $account=Account::find($id);
        $account->update($request->all());
        return response()->json([
            'type'=>'success',
            'message'=>'Account has been updated successfully'
        ]);
    }


    public function destroy($id)
    {
        $account=Account::find($id);
        $incomeCount=Income::whereAccountId($id)->orWhere('asset_id',$id)->count();
        $expenseCount=Expense::whereAccountId($id)->orWhere('asset_id',$id)->count();
        $expenseModelableCount=Expense::whereModelableId($id)->whereModelableType("App\Account")->count();
        $assetCount=Asset::whereAccountId($id)->orWhere('expense_id',$id)->orWhere('liability_id',$id)->count();
        $liabilitiesCount=Liability::whereAccountableId($id)->whereAccountableType("App\Account")->orWhere('asset_id',$id)->count();
        $count=($incomeCount+$expenseCount+$expenseModelableCount+$assetCount+$liabilitiesCount);
        if($count>0){
            return response()->json([
                'message'=>[
                    'type'=>'error',
                    'message'=>'Sorry!Already exits other transaction delete first then try'
                ],
            ]);
        }else{
            $account->delete();
            return response()->json([
                'message'=>[
                    'type'=>'success',
                    'message'=>'Account has been deleted successfully'
                ],
            ]);
        }

    }

    public function getAccountListData(){
        $expneses=DB::table('accounts')->where('account_type','Expense')->select('id','account_type','name');
        $prePaidExpense=DB::table('accounts')->where('account_type','Current Asset')->where('name', 'LIKE', "%pre%")->select('id','account_type','name');
        $query=DB::table('accounts')
            ->where('account_type','Income')
            ->select('id','account_type','name')
            ->union($expneses)
            ->union($prePaidExpense)
            ->orderBy('name','DESC');
        return $query;
    }

    public function setVariable(){
        $this->fromDate = $this->getFormDateAndToDate()['fromDate'];
        $this->toDate = $this->getFormDateAndToDate()['toDate'];
    }

    public function getDashboardData(){

        $monthlySales = $this->getMonthlySales();
        $weeklySales=$this->getWeeklySales();
        $monthlyExpense = $this->getMonthlyExpense();
        $weeklyExpense =$this->getWeeklyExpense();
        $cash =$this->getCash();
        $bank =$this->getBank();





        return response()->json([
            'weeklySales'=>$weeklySales,
            'monthlySales'=>$monthlySales,
            'weeklyExpenseData'=>$weeklyExpense,
            'monthlyExpenseData'=>$monthlyExpense,
            'cash'=>$cash,
            'bank'=>$bank,
        ]);

    }
}
