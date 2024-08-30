<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\DrugType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DrugTypeController extends Controller
{
    /**
     * Get the drug types list view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDrugTypeList()
    {
        $clinic = Clinic::getCurrentClinic();
        $drugTypes = $clinic->quantityTypes;

        return view('drugs.drugTypes.drugTypes', ['drugTypes' => $drugTypes]);
    }

    /**
     * Adds a new drug type.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addDrugType(Request $request)
    {
        $request->validate([
            'drugType' => 'required|string|max:255|unique:drug_types,drug_type,NULL,id,clinic_id,' . Clinic::getCurrentClinic()->id,
        ]);

        $clinic = Clinic::getCurrentClinic();
        $this->authorize('add', DrugType::class);

        try {
            $drugType = new DrugType();
            $drugType->drug_type = $request->drugType;
            $drugType->clinic()->associate($clinic);
            $drugType->creator()->associate(User::getCurrentUser());
            $drugType->save();

            return back()->with('success', $request->drugType . ' added successfully');
        } catch (\Exception $e) {
            Log::error("Failed to add drug type: " . $e->getMessage(), $request->all());
            return back()->withInput()->withErrors(['drugType' => 'Failed to add Drug Type']);
        }
    }

    /**
     * Delete a drug type.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteDrugType($id)
    {
        $drugType = DrugType::findOrFail($id);
        $this->authorize('delete', $drugType);

        try {
            $drugType->delete();

            return back()->with('success', $drugType->drug_type . " successfully deleted!");
        } catch (\Exception $e) {
            Log::error("Failed to delete drug type: " . $e->getMessage());
            return back()->with('error', 'Unable to delete ' . $drugType->drug_type . ". It may be associated with Drugs.");
        }
    }
}
