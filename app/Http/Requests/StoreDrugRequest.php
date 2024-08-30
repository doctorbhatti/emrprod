<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDrugRequest extends FormRequest
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
            'quantity'     => 'nullable|numeric',
            'manufacturedDate' => 'nullable|date|date_format:Y/m/d|before:' . now()->toDateString() . '|after:' . now()->subYears(100)->toDateString(),
            'receivedDate' => 'nullable|date|date_format:Y/m/d|before:' . now()->toDateString() . '|after:' . $this->manufacturedDate,
            'expiryDate'   => 'nullable|date|date_format:Y/m/d|after:' . now()->toDateString(),
        ];
    }
}
