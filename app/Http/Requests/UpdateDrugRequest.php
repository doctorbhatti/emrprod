<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDrugRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ensure proper authorization logic
    }

    public function rules()
    {
        return [
            'drugName'     => 'required|min:2',
            'ingredient'   => 'required|min:2',
            'manufacturer' => 'required|min:2',
            'quantityType' => 'required|exists:drug_types,id',
        ];
    }
}
