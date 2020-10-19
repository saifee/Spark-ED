<?php

namespace App\Http\Controllers\Amsl;

use App\Models\Amsl\Account;
use App\Http\Requests\Amsl\LiabilityRequest;
use App\Models\Amsl\Liability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LiabilityController extends Controller
{
    public function index(Request $request)
    {
        $query=Liability::with('accountable','accountReceivable');
        return $this->getListForUI($query, $request);
    }

    public function create()
    {
        $employee=DB::table('employees')->select('id','name',DB::raw("'Employee' as account_type"),DB::raw("'App\\\Employee' as accountable_type"));
        $query=DB::table('accounts')
            ->select('id','name','account_type',DB::raw("'App\\\Account' as accountable_type"))->whereIn('account_type',['Long-term Liabilities','Short-term Liabilities','Liabilities-AP'])
            ->union($employee)
            ->get();
        return response()->json([
            'accounts'=>$query,
            'receivableHolders'=>Account::whereAccountType('Current Asset-AR')->get(),
        ]);
    }


    public function store(LiabilityRequest $request)
    {
        $request->createPersist();
        return response()->json([
            'type'=>'success',
            'message'=>'Liability has been created successfully'
        ]);


    }

    public function show(Asset $employee)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        $employee=DB::table('employees')->select('id','name',DB::raw("'Employee' as account_type"),DB::raw("'App\\\Employee' as accountable_type"));
        $query=DB::table('accounts')
            ->select('id','name','account_type',DB::raw("'App\\\Account' as accountable_type"))->whereIn('account_type',['Long-term Liabilities','Short-term Liabilities','Liabilities-AP'])
            ->union($employee)
            ->get();
        return response()->json([
            'liability'=>Liability::with('accountable','accountReceivable')->whereId($id)->first(),
            'accounts'=>$query,
            'receivableHolders'=>Account::whereAccountType('Current Asset-AR')->get(),
        ]);
    }

    public function update(LiabilityRequest $request, $id)
    {
        $request->updatePersist($id);
        return response()->json([
            'type'=>'success',
            'message'=>'Liability has been updated successfully'
        ]);


    }


    public function destroy($id)
    {
        $income=Liability::find($id);
        $income->delete();
        return response()->json([
            'message'=>[
                'type'=>'success',
                'message'=>'Liability has been deleted successfully'
            ],
        ]);
    }
}
