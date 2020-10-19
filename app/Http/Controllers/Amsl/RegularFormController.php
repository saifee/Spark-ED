<?php

namespace App\Http\Controllers\Amsl;

use App\Http\Requests\Amsl\RegularFormRequest;
use App\Models\Amsl\RegularForm;
use Illuminate\Http\Request;

class RegularFormController extends Controller
{

    public function index()
    {
        $query=RegularForm::select();
        return $this->getListForUI($query, request());
    }


    public function create()
    {
        //
    }


    public function store(RegularFormRequest $request)
    {
        $request->createPersist();
        return response()->json([
            'type'=>'success',
            'message'=>'Record has been added successfully'
        ]);
    }

    public function show(RegularForm $regularForm)
    {
        //
    }


    public function edit($id)
    {
       return RegularForm::find($id);
    }

    public function update(RegularFormRequest $request, $id)
    {
        $request->updatePersist($id);
        return response()->json([
            'type'=>'success',
            'message'=>'Record has been updated successfully'
        ]);
    }


    public function destroy($id)
    {
        $regular=RegularForm::find($id);
        $regular->delete();
        return response()->json([
            'message'=>[
                'type'=>'success',
                'message'=>'Record has been deleted successfully'
            ],
        ]);
    }
}
