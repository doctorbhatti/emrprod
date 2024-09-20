<?php

namespace App\Http\Requests;

use App\Models\Clinic;
use App\Models\DosageFrequency;
use Illuminate\Foundation\Http\FormRequest;

class AddFrequencyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'frequencyDescription' => 'required|string|unique:frequencies,description,NULL,id,clinic_id,' . Clinic::getCurrentClinic()->id,
        ];
    }
}
