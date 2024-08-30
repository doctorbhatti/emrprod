<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Dosage;
use App\Models\DosageFrequency;
use App\Models\DosagePeriod;
use App\Models\Drug;
use App\Models\DrugType;
use App\Lib\Logger;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DrugAPIController extends Controller {
    public function getDosages() {
        $clinic = Clinic::getCurrentClinic();
        return response()->json(['status' => 1, 'dosages' => $clinic->dosages]);
    }

    public function getFrequencies() {
        $clinic = Clinic::getCurrentClinic();
        return response()->json(['status' => 1, 'frequencies' => $clinic->dosageFrequencies]);
    }

    public function getPeriods() {
        $clinic = Clinic::getCurrentClinic();
        return response()->json(['status' => 1, 'periods' => $clinic->dosagePeriods]);
    }

    public function getQuantityTypes() {
        $clinic = Clinic::getCurrentClinic();
        return response()->json(['status' => 1, 'quantityTypes' => $clinic->quantityTypes]);
    }

    /**
     * Saves a new drug which will immediately be added to the prescription.
     *
     * @param Request $request Request containing all the information about drugs and dosages
     * @return \Illuminate\Http\JsonResponse Response: status-1 is Success, status-0 is failure
     */
    public function saveDrugWithDosages(Request $request) {
        $clinic = Clinic::getCurrentClinic();
        $validator = Validator::make($request->all(), [
            'drug' => 'required_without:drugName|numeric|exists:drugs,id,clinic_id,' . $clinic->id,
            'drugName' => 'required_without:drug|max:200|min:2',
            'quantityType' => 'required_with:drugName|alpha|min:2|max:100',
            'dosage' => 'required_without:dosageText|exists:dosages,id,clinic_id,' . $clinic->id,
            'dosageText' => 'required_without:dosage|min:2|max:100',
            'frequency' => 'exists:dosage_frequencies,id,clinic_id,' . $clinic->id,
            'frequencyText' => 'min:2|max:100',
            'period' => 'exists:dosage_periods,id,clinic_id,' . $clinic->id,
            'periodText' => 'min:2|max:100',
        ]);

        if ($validator->fails()) {
            Logger::error("Validation error", $validator->errors()->toArray());
            return response()->json(['status' => 0, 'errors' => $validator->errors()]);
        }

        $user = User::getCurrentUser();
        DB::beginTransaction();
        try {
            $dosage = $this->addDosage($request, $clinic, $user);
            $frequency = $this->addFrequency($request, $clinic, $user);
            $period = $this->addPeriod($request, $clinic, $user);
            $quantityType = $this->addQuantityType($request, $clinic, $user);
            $drug = $this->addDrug($request, $clinic, $user, $quantityType);

            $prescriptionDrug = [
                'drug' => $drug,
                'dose' => $dosage,
                'frequency' => $frequency,
                'period' => $period
            ];
        } catch (Exception $e) {
            Logger::error($e->getMessage(), $request->all());
            DB::rollBack();
            return response()->json(['status' => 0]);
        }
        DB::commit();

        return response()->json(['status' => 1, 'drug' => $prescriptionDrug]);
    }

    private function addDosage(Request $request, Clinic $clinic, User $user) {
        $dosage = $request->dosage
            ? $clinic->dosages()->find($request->dosage)
            : $clinic->dosages()->where("description", 'LIKE', $request->dosageText)->first();

        if (is_null($dosage)) {
            $dosage = new Dosage();
            $dosage->description = $request->dosageText;
            $dosage->creator()->associate($user);
            $clinic->dosages()->save($dosage);
        }
        return $dosage;
    }

    private function addFrequency(Request $request, Clinic $clinic, User $user) {
        $frequency = $request->frequency
            ? $clinic->dosageFrequencies()->find($request->frequency)
            : $clinic->dosageFrequencies()->where("description", 'LIKE', $request->frequencyText)->first();

        if (is_null($frequency)) {
            $frequency = new DosageFrequency();
            $frequency->description = $request->frequencyText;
            $frequency->creator()->associate($user);
            $clinic->dosageFrequencies()->save($frequency);
        }
        return $frequency;
    }

    private function addPeriod(Request $request, Clinic $clinic, User $user) {
        $period = $request->period
            ? $clinic->dosagePeriods()->find($request->period)
            : $clinic->dosagePeriods()->where("description", 'LIKE', $request->periodText)->first();

        if (is_null($period)) {
            $period = new DosagePeriod();
            $period->description = $request->periodText;
            $period->creator()->associate($user);
            $clinic->dosagePeriods()->save($period);
        }
        return $period;
    }

    private function addQuantityType(Request $request, Clinic $clinic, User $user) {
        if (!isset($request->quantityType)) {
            return null;
        }

        $quantityType = $clinic->quantityTypes()->where('drug_type', 'LIKE', $request->quantityType)->first();
        if (is_null($quantityType)) {
            $quantityType = new DrugType();
            $quantityType->drug_type = $request->quantityType;
            $quantityType->creator()->associate($user);
            $clinic->quantityTypes()->save($quantityType);
        }
        return $quantityType;
    }

    private function addDrug(Request $request, Clinic $clinic, User $user, DrugType $quantityType = null) {
        $drug = $request->drug
            ? $clinic->drugs()->find($request->drug)
            : $clinic->drugs()->where('name', $request->drugName)
                ->where('drug_type_id', $quantityType ? $quantityType->id : null)
                ->first();

        if (is_null($drug)) {
            $drug = new Drug();
            $drug->name = $request->drugName;
            $drug->manufacturer = "N/A";
            $drug->quantityType()->associate($quantityType);
            $drug->creator()->associate($user);
            $clinic->drugs()->save($drug);
        }
        return $drug;
    }
}
