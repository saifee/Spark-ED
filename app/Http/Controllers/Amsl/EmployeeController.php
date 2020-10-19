<?php

namespace App\Http\Controllers\Amsl;

use App\Models\Amsl\Employee;
use App\Models\Amsl\EmployeeHistory;
use App\Models\Amsl\Expense;
use App\Http\Requests\AmslEmployeeRequest;
use App\Models\Amsl\Liability;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        $query=Employee::select();
        return $this->getListForUI($query, $request);
    }


    public function create()
    {

    }


    public function store(EmployeeRequest $request)
    {
        Employee::create($request->all());
        return response()->json([
            'type'=>'success',
            'message'=>'Employee has been created successfully'
        ]);

    }

    public function show(Employee $employee)
    {
        //
    }

    public function edit(Request $request, $id)
    {
       return Employee::find($id);
    }

    public function update(EmployeeRequest $request, $id)
    {
        $employee=Employee::find($id);
        $employee->update($request->all());
        return response()->json([
            'type'=>'success',
            'message'=>'Employee has been updated successfully'
        ]);
    }


    public function destroy($id)
    {
        $employee=Employee::find($id);
        $employeeHistoryCount=EmployeeHistory::whereEmployeeId($id)->count();
        $expenseModelableCount=Expense::whereModelableId($id)->whereModelableType("App\Employee")->count();
        $liabilitiesCount=Liability::whereAccountableId($id)->whereAccountableType("App\Employee")->count();
        $count=($employeeHistoryCount+$expenseModelableCount+$liabilitiesCount);
        if($count>0){
            return response()->json([
                'message'=>[
                    'type'=>'error',
                    'message'=>'Sorry!Already exits other transaction delete first then try'
                ],
            ]);
        }else{
        $employee->delete();
        return response()->json([
            'message'=>[
                'type'=>'success',
                'message'=>'Employee has been deleted successfully'
            ],
        ]);
        }
    }
}
