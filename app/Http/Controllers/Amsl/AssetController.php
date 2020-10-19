<?php

namespace App\Http\Controllers\Amsl;

use App\Models\Amsl\Account;
use App\Models\Amsl\Asset;
use App\Models\Amsl\Expense;
use App\Http\Requests\Amsl\AssetRequest;
use App\Models\Amsl\Income;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query=Asset::with('account','accountPayable');
        return $this->getListForUI($query, $request);
    }

    public function create()
    {
        return response()->json([
            'accounts'=>Account::whereIn('account_type',['Fixed Asset','Current Asset','Current Asset-AR'])->get(),
            'payableHolders'=>Account::whereAccountType('Liabilities-AP')->get(),
            'expenses'=>Account::whereAccountType('Expense')->get(),
        ]);
    }


    public function store(AssetRequest $request)
    {
        $request->createPersist();
        return response()->json([
            'type'=>'success',
            'message'=>'Asset has been created successfully'
        ]);


    }

    public function show(Asset $employee)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        return response()->json([
            'asset'=>Asset::with('account','accountPayable')->whereId($id)->first(),
            'accounts'=>Account::whereIn('account_type',['Fixed Asset','Current Asset','Current Asset-AR'])->get(),
            'payableHolders'=>Account::whereAccountType('Liabilities-AP')->get(),
        ]);
    }

    public function update(AssetRequest $request, $id)
    {
        $request->updatePersist($id);
        return response()->json([
            'type'=>'success',
            'message'=>'Asset has been updated successfully'
        ]);


    }


    public function destroy($id)
    {
        $asset=Asset::find($id);
        $asset->delete();
        return response()->json([
            'message'=>[
                'type'=>'success',
                'message'=>'Asset has been deleted successfully'
            ],
        ]);
    }
}
