<?php

namespace App\Http\Requests\Amsl;

use App\Helpers\GlobalMethod;
use App\Models\Amsl\Income;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class IncomeRequest extends FormRequest
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
        ];
    }

    public  function createPersist(){
        $globalMethod=new GlobalMethod();
        $this->merge([
            'income_date'=>Carbon::parse($globalMethod->parseDate($this->input('income_date'))),
        ]);

        $income=new Income();
        $income->fill($this->except('account'));
        $income->account()->associate($this->input('account.id'));
        $income->save();
    }

    public function updatePersist($id){
        $globalMethod=new GlobalMethod();
        $this->merge([
            'income_date'=>Carbon::parse($globalMethod->parseDate($this->input('income_date'))),
        ]);
        $inocme=Income::find($id);
        $inocme->fill($this->except('account'));
        $inocme->account()->associate($this->input('account.id'));
        $inocme->update();
    }
}
