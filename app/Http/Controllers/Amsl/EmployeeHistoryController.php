<?php

namespace App\Http\Controllers\Amsl;

use App\Models\Amsl\Employee;
use App\Models\Amsl\EmployeeHistory;
use App\Http\Requests\Amsl\EmployeeHistroyRequest;
use Illuminate\Http\Request;

class EmployeeHistoryController extends Controller
{

    public function index(Request $request)
    {
        $query=EmployeeHistory::with('employee');
        return $this->getListForUI($query, $request);
    }


    public function create()
    {
        return Employee::all();
    }


    public function store(EmployeeHistroyRequest $request)
    {
        $request->createPersist();
        return response()->json([
            'type'=>'success',
            'message'=>'Employee History has been created successfully'
        ]);

    }

    public function show(EmployeeHistory $employeeHistory)
    {
        //
    }

    public function edit(Request $request,$id)
    {

        $history=EmployeeHistory::with('employee')->whereId($id)->first();
        $employees=Employee::all();
        return response()->json([
            'history'=>$history,
            'employees'=>$employees
        ]);

    }


    public function update(EmployeeHistroyRequest $request,$id)
    {
        $history=EmployeeHistory::find($id);
        $request->updatePersist($id);
        return response()->json([
            'type'=>'success',
            'message'=>'Employee History has been updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $history=EmployeeHistory::find($id);
        $history->delete();
        return response()->json([
            'message'=>[
                'type'=>'success',
                'message'=>'Employee History has been deleted successfully'
            ],
        ]);
    }
}
