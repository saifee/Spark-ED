<?php

namespace App\Http\Requests\Amsl;

use App\Models\Amsl\Asset;
use App\Helpers\GlobalMethod;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AssetRequest extends FormRequest
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
            'asset_date'=>Carbon::parse($globalMethod->parseDate($this->input('asset_date'))),
        ]);
        $asset=new Asset();
        $asset->fill($this->except('account'));
        $asset->account()->associate($this->input('account.id'));
        $asset->save();

    }

    public function updatePersist($id){

        $globalMethod=new GlobalMethod();
        $this->merge([
            'asset_date'=>Carbon::parse($globalMethod->parseDate($this->input('asset_date'))),
        ]);
        $asset=Asset::find($id);
        $asset->fill($this->except('account'));
        $asset->account()->associate($this->input('account.id'));
        $asset->update();
    }
}
