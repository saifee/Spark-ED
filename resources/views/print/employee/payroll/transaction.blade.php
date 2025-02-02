@include('print.print-layout.header')
    <h2 style="text-align: center;">{{config('config.default_academic_session.name')}}</h2>
    <h2>{{trans('employee.payroll_transaction').' '.trans('general.total_result_count',['count' => count($payroll_transactions)])}}</h2>
    @if(isset(request('filter')['employee_id']) && filled(request('filter')['employee_id']))
    <h2>Employees: {{implode(',', \App\Models\Employee\Employee::select('first_name','middle_name','last_name')->whereIn('id', request('filter')['employee_id'])->get()->pluck('name')->toArray())}}</h2>
    @endif
    @if(isset(request('filter')['department_id']) && filled(request('filter')['department_id']))
    <h2>Departments: {{implode(',', \App\Models\Configuration\Employee\Department::select('name')->whereIn('id', request('filter')['department_id'])->get()->pluck('name')->toArray())}}</h2>
    @endif
    <table class="fancy-detail">
        <thead>
            <tr>
                <th>{{trans('finance.voucher_number')}}</th>
                <th>{{trans('employee.employee')}}</th>
                <th>{{trans('employee.payroll_transaction_head')}}</th>
                <th>{{trans('finance.account')}}</th>
                <th>{{trans('finance.payment_method')}}</th>
                <th>{{trans('finance.amount')}}</th>
                <th>{{trans('finance.date_of_transaction')}}</th>
                <th>{{trans('finance.fee_payment_remarks')}}</th>
                <th>{{trans('general.created_by')}}</th>
                <th>{{trans('general.created_at')}}</th>
            </tr>
        </thead>
        <tbody>
        	@foreach($payroll_transactions as $payroll_transaction)
        		<tr>
                    <td>{{$payroll_transaction->voucher_number}}</td>
                    <td>
                        {{$payroll_transaction->Employee->name_with_code}} <br />
                        <small>{{getEmployeeDesignationName($payroll_transaction->Employee, $payroll_transaction->date)}}</small>
                    </td>
                    <td>{{trans('employee.payroll_transaction_'.$payroll_transaction->head)}}</td>
                    <td>{{$payroll_transaction->account->name}}</td>
                    <td>
                        {{$payroll_transaction->paymentMethod->name}}
                    </td>
                    <td>{{currency($payroll_transaction->amount,1)}}</td>
                    <td>{{showDate($payroll_transaction->date)}}</td>
                    <td>{{$payroll_transaction->remarks}}</td>
                    <td>{{$payroll_transaction->user->employee->name.' '.getEmployeeDesignationName($payroll_transaction->user->employee, $payroll_transaction->date)}}</td>
                    <td>{{showDateTime($payroll_transaction->created_at)}}</td>
        		</tr>
        	@endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5"></th>
                <th>{{currency($footer['grand_total'],1)}}</th>
                <th colspan="4"></th>
            </tr>
        </tfoot>
    </table>
@include('print.print-layout.footer')