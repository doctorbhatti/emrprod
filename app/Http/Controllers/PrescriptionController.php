<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PrescriptionController extends Controller
{
    /**
     * Show the issue medicine page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewIssueMedicine()
    {
        Gate::authorize('issueMedicine', 'App\Models\Patient');
        
        return view('prescriptions.issueMedicine');
    }

    /**
     * Get the print preview of a prescription.
     *
     * @param int $id
     * @param int $prescriptionId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function prescriptionPrintPreview($id, $prescriptionId)
    {
        try {
            $patient = Patient::findOrFail($id);
            $prescription = Prescription::findOrFail($prescriptionId);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Patient or Prescription not found.');
        }
    
        Gate::authorize('printPrescription', [$prescription, $patient]);
    
        // Retrieve the print preview option from settings
        $setting = Setting::first(); // Adjust this to get the specific setting for the user or default one
        $printPreviewOption = $setting ? $setting->print_preview_option : 'default_option'; // fallback to a default option if not found
    
        return view('prescriptions.printPreview', [
            'patient' => $patient,
            'prescription' => $prescription,
            'printPreviewOption' => $printPreviewOption // Pass the print preview option to the view
        ]);
    }
    
    /**
     * Display the list of prescriptions for payments.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPayments()
    {
        Gate::authorize('view', 'App\Models\Payment');

        $clinic = Clinic::getCurrentClinic();
        $prescriptions = $clinic->prescriptions()
            ->with('patient')
            ->orderBy('issued_at', 'desc')
            ->get();

        return view('prescriptions.payments', compact('prescriptions'));
    }
}
