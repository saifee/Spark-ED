<?php

namespace App\Http\Controllers\Amsl;

use App\Models\Amsl\Account;
use App\Models\Amsl\Category;
use App\Models\Amsl\Employee;
use App\Models\Amsl\Expense;
use App\Http\Requests\AmslExpenseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query=Expense::with('account','modelable');
        return $this->getListForUI($query, $request);
    }


    public function create()
    {
        $employee=DB::table('employees')->select('id','name',DB::raw("'App\\\Employee' AS payable_type"));
        $payableHolders=DB::table('accounts')
            ->select('id','name',DB::raw("'App\\\Account' AS payable_type"))
            ->where('account_type','Liabilities-AP')
            ->union($employee)
            ->orderBy('name')
            ->get();
        return response()->json([
           'accounts'=>Account::whereAccountType('Expense')->get(),
           'payableHolders'=>$payableHolders,
           'assets'=>Account::whereAccountType('Fixed Asset')->get(),
           'prepaidAssets'=>Account::whereAccountType('Current Asset')->where('name', 'LIKE', "%prepaid%")->get(),
           'employees'=>Employee::all(),
        ]);
    }


    public function store(ExpenseRequest $request)
    {
        $request->createPersist();
        return response()->json([
            'type'=>'success',
            'message'=>'Expense has been created successfully'
        ]);

    }

    public function show(Expense $employee)
    {
        //
    }

    public function edit(Request $request, $id)
    {

        $employee=DB::table('employees')->select('id','name',DB::raw("'App\\\Employee' AS payable_type"));
        $payableHolders=DB::table('accounts')
            ->select('id','name',DB::raw("'App\\\Account' AS payable_type"))
            ->where('account_type','Liabilities-AP')
            ->orderBy('name')
            ->union($employee)
            ->get();
        return response()->json([
            'accounts'=>Account::whereAccountType('Expense')->get(),
            'payableHolders'=>$payableHolders,
            'assets'=>Account::whereAccountType('Fixed Asset')->get(),
            'employees'=>Employee::all(),
            'prepaidAssets'=>Account::whereAccountType('Current Asset')->where('name', 'LIKE', "%prepaid%")->get(),
            'expense'=>Expense::with('account','employee','modelable','asset')->whereId($id)->first(),
        ]);


    }

    public function update(ExpenseRequest $request, $id)
    {

        $request->updatePersist($id);
        return response()->json([
            'type'=>'success',
            'message'=>'Expense has been updated successfully'
        ]);
    }


    public function destroy($id)
    {
        $expense=Expense::find($id);
        $expense->delete();
        return response()->json([
            'message'=>[
                'type'=>'success',
                'message'=>'Employe has been deleted successfully'
            ],
        ]);
    }
}
