<?php

namespace App\Http\Requests\Amsl;

use App\EmployeeHistory;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeHistroyRequest extends FormRequest
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
            'employee' => 'required',
            'start_date' => 'required',
        ];
    }
    public  function createPersist(){
        $this->merge(['start_date'=>Carbon::parse($this->input('start_date'))]);

        if($this->end_date){
          $this->merge(['end_date'=>Carbon::parse($this->input('end_date'))]);
        }else{
            $this->merge(['end_date'=>null]);
        }


        $history= new EmployeeHistory();
        $history->fill($this->except('employee'));
        $history->employee()->associate($this->input('employee.id'));
        $history->save();
    }

    public function updatePersist($id){
        $this->merge(['start_date'=>Carbon::parse($this->input('start_date'))]);

        if($this->end_date){
            $this->merge(['end_date'=>Carbon::parse($this->input('end_date'))]);
        }else{
            $this->merge(['end_date'=>null]);
        }

        $history=EmployeeHistory::find($id);
        $history->fill($this->except('employee'));
        $history->employee()->associate($this->input('employee.id'));
        $history->update();
    }
}
