<?php

namespace App\Http\Requests;

use App\Models\Clinic;
use App\Models\DosagePeriod;
use Illuminate\Foundation\Http\FormRequest;

class AddPeriodRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'description' => 'required|string|unique:periods,description,NULL,id,clinic_id,' . Clinic::getCurrentClinic()->id,
        ];
    }
}
