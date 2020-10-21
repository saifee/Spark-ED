<?php

namespace App\Http\Requests\Amsl;

use App\Helpers\GlobalMethod;
use App\Models\Amsl\Ownerequity;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class OwnerEquityRequest extends FormRequest
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
            'equity_date'=>Carbon::parse($globalMethod->parseDate($this->input('equity_date'))),
        ]);
        $asset=new Ownerequity();
        $asset->fill($this->all());
        $asset->save();

    }

    public function updatePersist($id){

        $globalMethod=new GlobalMethod();
        $this->merge([
            'equity_date'=>Carbon::parse($globalMethod->parseDate($this->input('equity_date'))),
        ]);
        $asset=Ownerequity::find($id);
        $asset->fill($this->all());
        $asset->update();
    }
}
