<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Dosage;
use App\Models\DosageFrequency;
use App\Models\DosagePeriod;
use App\Models\User;
use App\Http\Requests\AddDosageRequest;
use App\Http\Requests\EditDosageRequest;
use App\Http\Requests\AddFrequencyRequest;
use App\Http\Requests\EditFrequencyRequest;
use App\Http\Requests\AddPeriodRequest;
use App\Http\Requests\EditPeriodRequest;
use Illuminate\Support\Facades\DB;
use Log;

class DosageController extends Controller
{
    public function getDosageList()
    {
        $clinic = Clinic::getCurrentClinic();
        $dosages = $clinic->dosages;
        $frequencies = $clinic->dosageFrequencies;
        $periods = $clinic->dosagePeriods;

        return view('drugs.dosages.dosages', [
            'dosages' => $dosages,
            'frequencies' => $frequencies,
            'periods' => $periods,
        ]);
    }

    public function addDosage(AddDosageRequest $request)
    {
        $clinic = Clinic::getCurrentClinic();

        $dosage = new Dosage();
        $dosage->description = $request->dosageDescription;
        $dosage->clinic()->associate($clinic);
        $dosage->creator()->associate(User::getCurrentUser());
        $dosage->save();

        return back()->with('success', "Dosage added successfully!");
    }

    public function addFrequency(AddFrequencyRequest $request)
    {
        $clinic = Clinic::getCurrentClinic();

        $frequency = new DosageFrequency();
        $frequency->description = $request->frequencyDescription;
        $frequency->clinic()->associate($clinic);
        $frequency->creator()->associate(User::getCurrentUser());
        $frequency->save();

        return back()->with('success', "Dosage Frequency added successfully!");
    }

    public function addPeriod(AddPeriodRequest $request)
    {
        $clinic = Clinic::getCurrentClinic();

        $period = new DosagePeriod();
        $period->description = $request->description;
        $period->clinic()->associate($clinic);
        $period->creator()->associate(User::getCurrentUser());
        $period->save();

        return back()->with('success', "Dosage Period added successfully!");
    }

    public function editDosage(Dosage $dosage, EditDosageRequest $request)
    {
        $dosage->description = $request->dosage;
        $dosage->save();

        return back()->with('success', "Dosage description updated!");
    }

    public function editFrequency(DosageFrequency $dosageFrequency, EditFrequencyRequest $request)
    {
        $dosageFrequency->description = $request->frequency;
        $dosageFrequency->save();

        return back()->with('success', "Dosage frequency description updated!");
    }

    public function editPeriod(DosagePeriod $period, EditPeriodRequest $request)
    {
        $period->description = $request->period;
        $period->save();

        return back()->with('success', "Period description updated!");
    }

    public function deleteDosage(Dosage $dosage)
    {
        $this->authorize('delete', $dosage);

        DB::beginTransaction();

        try {
            $dosage->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'The entry cannot be deleted');
        }

        return back()->with('success', 'Entry deleted successfully');
    }
    public function deleteFrequency($id)
{
    $dosageFrequency = DosageFrequency::findOrFail($id);
    $this->authorize('delete', $dosageFrequency);

    DB::beginTransaction();

    try {
        Log::info('Attempting to delete Dosage Frequency:', ['id' => $dosageFrequency->id]);
        $dosageFrequency->delete();
        DB::commit();
        Log::info('Dosage Frequency deleted successfully:', ['id' => $dosageFrequency->id]);
    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Error deleting Dosage Frequency:', ['error' => $e->getMessage()]);
        return back()->with('error', 'The entry cannot be deleted');
    }

    return back()->with('success', 'Entry deleted successfully');
}


public function deletePeriod($id)
{
    // Fetch the DosagePeriod model by ID
    $period = DosagePeriod::findOrFail($id);
    $this->authorize('delete', $period);

    DB::beginTransaction();

    try {
        \Log::info('Attempting to delete Dosage Period:', ['id' => $period->id]);
        $period->delete();
        DB::commit();
        \Log::info('Dosage Period deleted successfully:', ['id' => $period->id]);
    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('Error deleting Dosage Period:', ['error' => $e->getMessage()]);
        return back()->with('error', 'The entry cannot be deleted');
    }

    return back()->with('success', 'Entry deleted successfully');
}

}
