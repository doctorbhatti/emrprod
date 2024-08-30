<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Dosage;
use App\Models\DosageFrequency;
use App\Models\DosagePeriod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DosageController extends Controller {

    /**
     * Get dosages as a list
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDosageList() {
        $clinic = Clinic::getCurrentClinic();
        $dosages = $clinic->dosages;
        $frequencies = $clinic->dosageFrequencies;
        $periods = $clinic->dosagePeriods;

        return view('drugs.dosages.dosages', [
            'dosages' => $dosages,
            'frequencies' => $frequencies,
            'periods' => $periods
        ]);
    }

    /**
     * Adds a new Dosage to the system
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addDosage(Request $request) {
        $this->authorize('add', Dosage::class);

        $validator = Validator::make($request->all(), [
            'dosageDescription' => 'required|unique:dosages,description,NULL,id,clinic_id,' . Clinic::getCurrentClinic()->id
        ]);

        if ($validator->fails()) {
            return back()->with('type', 'dosage')->withErrors($validator)->withInput();
        }

        try {
            $dosage = new Dosage();
            $dosage->description = $request->dosageDescription;
            $dosage->clinic()->associate(Clinic::getCurrentClinic());
            $dosage->creator()->associate(User::getCurrentUser());
            $dosage->save();
        } catch (\Exception $e) {
            return back()->with('type', 'dosage')->with('error', 'Unable to add the dosage')->withInput();
        }

        return back()->with('success', "Dosage added successfully !");
    }

    /**
     * Adds a new Dosage Frequency to the system
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addFrequency(Request $request) {
        $this->authorize('add', DosageFrequency::class);

        $validator = Validator::make($request->all(), [
            'frequencyDescription' => 'required|unique:dosage_frequencies,description,NULL,id,clinic_id,' . Clinic::getCurrentClinic()->id
        ]);

        if ($validator->fails()) {
            return back()->with('type', 'frequency')->withErrors($validator)->withInput();
        }

        try {
            $frequency = new DosageFrequency();
            $frequency->description = $request->frequencyDescription;
            $frequency->clinic()->associate(Clinic::getCurrentClinic());
            $frequency->creator()->associate(User::getCurrentUser());
            $frequency->save();
        } catch (\Exception $e) {
            return back()->with('type', 'frequency')->with('error', 'Unable to add the dosage frequency')->withInput();
        }

        return back()->with('success', "Dosage Frequency added successfully !");
    }

    /**
     * Adds a new Dosage Period to the system
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPeriod(Request $request) {
        $this->authorize('add', DosagePeriod::class);

        $validator = Validator::make($request->all(), [
            'description' => 'required|unique:dosage_periods,description,NULL,id,clinic_id,' . Clinic::getCurrentClinic()->id
        ]);

        if ($validator->fails()) {
            return back()->with('type', 'period')->withErrors($validator)->withInput();
        }

        try {
            $period = new DosagePeriod();
            $period->description = $request->description;
            $period->clinic()->associate(Clinic::getCurrentClinic());
            $period->creator()->associate(User::getCurrentUser());
            $period->save();
        } catch (\Exception $e) {
            return back()->with('type', 'period')->with('error', 'Unable to add the dosage period')->withInput();
        }

        return back()->with('success', "Dosage Period added successfully !");
    }

    /**
     * Edits a dosage
     *
     * @param $id dosage id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editDosage($id, Request $request) {
        $dosage = Dosage::findOrFail($id);
        $this->authorize('edit', $dosage);

        $validator = Validator::make($request->all(), [
            'dosage' => 'required|string'
        ]);

        if ($validator->fails()) {
            return back()->with('error', "Please enter a valid description for the dosage")->withErrors($validator)->withInput();
        }

        $dosage->description = $request->dosage;
        $dosage->save();

        return back()->with('success', "Dosage description updated !");
    }

    /**
     * Edits a frequency
     *
     * @param $id DosageFrequency id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editFrequency($id, Request $request) {
        $dosageFrequency = DosageFrequency::findOrFail($id);
        $this->authorize('edit', $dosageFrequency);

        $validator = Validator::make($request->all(), [
            'frequency' => 'required|string'
        ]);

        if ($validator->fails()) {
            return back()->with('error', "Please enter a valid description for the dosage frequency")->withErrors($validator)->withInput();
        }

        $dosageFrequency->description = $request->frequency;
        $dosageFrequency->save();

        return back()->with('success', "Dosage frequency description updated !");
    }

    /**
     * Edits a period
     *
     * @param $id DosagePeriod id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editPeriod($id, Request $request) {
        $period = DosagePeriod::findOrFail($id);
        $this->authorize('edit', $period);

        $validator = Validator::make($request->all(), [
            'period' => 'required|string'
        ]);

        if ($validator->fails()) {
            return back()->with('error', "Please enter a valid description for the period")->withErrors($validator)->withInput();
        }

        $period->description = $request->period;
        $period->save();

        return back()->with('success', "Period description updated !");
    }

    /**
     * Delete a dosage entry from the database
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteDosage($id) {
        $dosage = Dosage::findOrFail($id);
        $this->authorize('delete', $dosage);

        DB::beginTransaction();
        try {
            $dosage->delete();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'The entry cannot be deleted');
        }
        DB::commit();

        return back()->with('success', 'Entry deleted successfully');
    }

    /**
     * Delete a dosage frequency entry from the database
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteFrequency($id) {
        $dosageFrequency = DosageFrequency::findOrFail($id);
        $this->authorize('delete', $dosageFrequency);

        DB::beginTransaction();
        try {
            $dosageFrequency->delete();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'The entry cannot be deleted');
        }
        DB::commit();

        return back()->with('success', 'Entry deleted successfully');
    }

    /**
     * Delete a period entry from the database
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePeriod($id) {
        $dosagePeriod = DosagePeriod::findOrFail($id);
        $this->authorize('delete', $dosagePeriod);

        DB::beginTransaction();
        try {
            $dosagePeriod->delete();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'The entry cannot be deleted');
        }
        DB::commit();

        return back()->with('success', 'Entry deleted successfully');
    }
}
