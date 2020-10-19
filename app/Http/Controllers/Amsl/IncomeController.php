<?php

namespace App\Http\Controllers\Amsl;

use App\Models\Amsl\Account;
use App\Http\Requests\AmslIncomeRequest;
use App\Models\Amsl\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $query=Income::with('account','accountReceivable');
        return $this->getListForUI($query, $request);
    }

    public function create()
    {
        return response()->json([
            'accounts'=>Account::whereAccountType('Income')->get(),
            'receivableHolders'=>Account::whereAccountType('Current Asset-AR')->get(),
        ]);
    }


    public function store(IncomeRequest $request)
    {
        $request->createPersist();
        return response()->json([
            'type'=>'success',
            'message'=>'Income has been created successfully'
        ]);


    }

    public function show(Income $employee)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        return response()->json([
            'income'=>Income::with('account','accountReceivable')->whereId($id)->first(),
            'accounts'=>Account::whereAccountType('Income')->get(),
            'receivableHolders'=>Account::whereAccountType('Current Asset-AR')->get(),
        ]);
    }

    public function update(IncomeRequest $request, $id)
    {
        $request->updatePersist($id);
        return response()->json([
            'type'=>'success',
            'message'=>'Income has been updated successfully'
        ]);


    }


    public function destroy($id)
    {
        $income=Income::find($id);
        $income->delete();
        return response()->json([
            'message'=>[
                'type'=>'success',
                'message'=>'Income has been deleted successfully'
            ],
        ]);
    }
}
