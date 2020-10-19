<?php

namespace App\Http\Controllers\Amsl;

use App\Models\Amsl\Account;
use App\Http\Requests\Amsl\OwnerEquityRequest;
use App\Models\Amsl\Ownerequity;
use Illuminate\Http\Request;

class OwnerequityController extends Controller
{
    public function index(Request $request)
    {
        $query=Ownerequity::with('accountPayable');
        return $this->getListForUI($query, $request);
    }

    public function create()
    {
        return response()->json([
            'payableHolders'=>Account::whereAccountType('Liabilities-AP')->get(),
        ]);
    }


    public function store(OwnerEquityRequest $request)
    {
        $request->createPersist();
        return response()->json([
            'type'=>'success',
            'message'=>'Owner Equity has been created successfully'
        ]);


    }

    public function show(Asset $employee)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        return response()->json([
            'equity'=>Ownerequity::with('accountPayable')->whereId($id)->first(),
            'receivableHolders'=>Account::whereAccountType('Current Asset-AR')->get(),
        ]);
    }

    public function update(OwnerEquityRequest $request, $id)
    {
        $request->updatePersist($id);
        return response()->json([
            'type'=>'success',
            'message'=>'Owner Equity has been updated successfully'
        ]);


    }


    public function destroy($id)
    {
        $equity=Ownerequity::find($id);
        $equity->delete();
        return response()->json([
            'message'=>[
                'type'=>'success',
                'message'=>'Owner Equity has been deleted successfully'
            ],
        ]);
    }
}
