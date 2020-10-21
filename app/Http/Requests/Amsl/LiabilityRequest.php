<?php

namespace App\Http\Requests\Amsl;

use App\Helpers\GlobalMethod;
use App\Models\Amsl\Liability;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class LiabilityRequest extends FormRequest
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
            'account'=>'required',
            'transaction_type'=>'required',
            'payment_type'=>'required',
            'amount'=>'required',
            'ref'=>'required',
        ];
    }

    public function createPersist(){
        $globalMethod=new GlobalMethod();
        $this->merge([
            'liability_date'=>Carbon::parse($globalMethod->parseDate($this->input('liability_date'))),
            'accountable_id'=>$this->input('account.id'),
            'accountable_type'=>$this->input('account.accountable_type'),
        ]);

        $asset=new Liability();
        $asset->fill($this->except('account'));
        $asset->save();

    }

    public function updatePersist($id){
        $globalMethod=new GlobalMethod();
        $this->merge([
            'liability_date'=>Carbon::parse($globalMethod->parseDate($this->input('liability_date'))),
            'accountable_id'=>$this->input('account.id'),
            'accountable_type'=>$this->input('account.accountable_type'),
        ]);
        $asset=Liability::find($id);
        $asset->fill($this->except('account'));
        $asset->update();
    }
}
