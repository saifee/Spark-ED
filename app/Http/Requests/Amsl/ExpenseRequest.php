<?php

namespace App\Http\Requests\Amsl;

use App\Expense;
use App\Helpers\GlobalMethod;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account' => 'required',
            'amount' => 'required',
            'ref' => 'required',
            'payment_type' => 'required',
        ];
    }
    public function createPersist(){
        $globalMethod=new GlobalMethod();
        $this->merge([
            'expense_date'=>Carbon::parse($globalMethod->parseDate($this->input('expense_date'))),
        ]);
        $this->payableDetails();
        $expense=new Expense();
        $expense->fill($this->except('account'));
        $expense->account()->associate($this->input('account.id'));
        $expense->save();

        if($this->input('due_amount')){
            $this->merge(['payment_type'=>'Accounts Payable']);
            $this->merge(['modelable_type'=>'App\Employee']);
            $this->merge(['modelable_id'=>$this->input('employee_id')]);
            $this->merge(['amount'=>$this->input('due_amount')]);
            $this->merge(['tax_rate'=>$this->input('tax_rate')]);
            $this->merge(['tax_amount'=>($this->input('due_amount')*$this->input('tax_rate'))/100]);
            $this->merge(['after_tax_amount'=>$this->input('due_amount')-($this->input('due_amount')*$this->input('tax_rate'))/100]);
            $this->merge(['amount'=>$this->input('due_amount')]);
            $expense=new Expense();
            $expense->fill($this->except('account'));
            $expense->account()->associate($this->input('account.id'));
            $expense->save();
        }



    }


    public function updatePersist($id){
        $globalMethod=new GlobalMethod();
        $this->merge([
            'expense_date'=>Carbon::parse($globalMethod->parseDate($this->input('expense_date'))),
        ]);
        $this->updatePayableDetails();

        $expense=Expense::find($id);
        $expense->fill($this->except('account'));
        $expense->account()->associate($this->input('account.id'));
        $expense->update();


    }


    protected function payableDetails(){
        if($this->input('payable_details')){
            $this->merge([
                'modelable_id'=>$this->input('payable_details')['id'],
                'modelable_type'=>$this->input('payable_details')['payable_type'],
            ]);
            if($this->input('payable_details')['payable_type']=="App\Employee" && ($this->input('account')['name']=='Wages' ||$this->input('account')['name']=='Wage')||$this->input('account')['name']=='wages'||$this->input('account')['name']=='wage'){

                $this->merge([
                    'employee_id'=>$this->input('payable_details')['id'],
                ]);
            }
        }
    }


    protected function updatePayableDetails(){
        if($this->input('payable_details')){
            $this->merge([
                'modelable_id'=>$this->input('payable_details')['id'],
                'modelable_type'=>$this->input('payable_details')['payable_type'],
            ]);
            if($this->input('payable_details')['payable_type']=="App\Employee" && ($this->input('account')['name']=='Wages' ||$this->input('account')['name']=='Wage')||$this->input('account')['name']=='wages'||$this->input('account')['name']=='wage'){

                $this->merge([
                    'employee_id'=>$this->input('payable_details')['id'],
                ]);
            }
        }else{
            if(($this->input('account')['name']=='Wages' ||$this->input('account')['name']=='Wage')||$this->input('account')['name']=='wages'||$this->input('account')['name']=='wage'){
                if($this->input('employee')){
                    $this->merge([
                        'employee_id'=>$this->input('employee')['id'],
                    ]);
                }else{
                    $this->merge([
                        'employee_id'=>null,
                    ]);
                }

            }
            $this->merge([
                'modelable_id'=>null,
                'modelable_type'=>null,
            ]);
        }
    }


}
