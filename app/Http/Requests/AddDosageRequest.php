<?php

namespace App\Http\Requests;

use App\Models\Clinic;
use App\Models\Dosage;
use Illuminate\Foundation\Http\FormRequest;

class AddDosageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'dosageDescription' => 'required|string|unique:dosages,description,NULL,id,clinic_id,' . Clinic::getCurrentClinic()->id,
        ];
    }
}
