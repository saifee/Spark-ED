<?php

namespace App\Http\Requests\Configuration\Employee;

use Illuminate\Foundation\Http\FormRequest;

class AssetItemRequest extends FormRequest
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
        $rules = [
            'price' => 'numeric|min:0'
        ];

        $id = $this->route('id');

        if ($this->method() === 'POST') {
            $rules['name'] = 'required|unique:asset_items';
            $rules['serial_number'] = 'nullable|unique:asset_items';
        } elseif ($this->method() === 'PATCH') {
            $rules['name'] = 'required|unique:asset_items,name,'.$id.',id';
            $rules['serial_number'] = 'nullable|unique:asset_items,serial_number,'.$id.',id';
        }

        return $rules;
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => trans('inventory.asset_item_name'),
            'price' => trans('inventory.asset_item_price'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
        ];
    }
}