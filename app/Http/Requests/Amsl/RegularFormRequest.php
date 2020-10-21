<?php

namespace App\Http\Requests\Amsl;

use App\Helpers\GlobalMethod;
use App\Models\Amsl\RegularForm;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class RegularFormRequest extends FormRequest
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
            'name' => 'required',
        ];
    }

    public  function createPersist(){
        $globalMethod=new GlobalMethod();
        $this->merge([
            'date'=>Carbon::parse($globalMethod->parseDateTime($this->input('date'))),
        ]);
        $income=new RegularForm();
        $income->fill($this->except('account'));
        $income->save();
    }

    public function updatePersist($id){
        $globalMethod=new GlobalMethod();
        $this->merge([
            'date'=>Carbon::parse($globalMethod->parseDateTime($this->input('date'))),
        ]);
        $inocme=RegularForm::find($id);
        $inocme->fill($this->except('account'));
        $inocme->update();
    }
}
