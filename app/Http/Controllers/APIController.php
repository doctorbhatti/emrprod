<?php

namespace App\Http\Controllers;

use App\Models\Clinic; // Ensure using correct model namespace
use App\Models\Dosage; // Ensure using correct model namespace
use App\Models\DosageFrequency; // Ensure using correct model namespace
use App\Models\DosagePeriod; // Ensure using correct model namespace
use App\Models\Drug; // Ensure using correct model namespace
use App\Models\Patient; // Ensure using correct model namespace
use App\Models\Payment; // Ensure using correct model namespace
use App\Models\Prescription; // Ensure using correct model namespace
use App\Models\PrescriptionDrug; // Ensure using correct model namespace
use App\Models\PrescriptionPharmacyDrug; // Ensure using correct model namespace
use App\Models\Queue; // Ensure using correct model namespace
use App\Models\User; // Ensure using correct model namespace
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class APIController extends Controller
{
    /**
     * Get the clinic's drugs
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDrugs()
    {
        $clinic = Clinic::getCurrentClinic();
        $data = $clinic->drugs()->orderBy('name')->select('id', 'name', 'quantity')->get()->toArray();

        return response()->json($data);
    }

    /**
     * Get the Dosages, Frequencies, and Periods of the clinic
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDosages()
    {
        $clinic = Clinic::getCurrentClinic();
        $dosages = $clinic->dosages()->orderBy('description')->select('id', 'description')->get()->toArray();
        $frequencies = $clinic->dosageFrequencies()->orderBy('description')->select('id', 'description')->get()->toArray();
        $periods = $clinic->dosagePeriods()->orderBy('description')->select('id', 'description')->get()->toArray();

        return response()->json(['dosages' => $dosages, 'frequencies' => $frequencies, 'periods' => $periods]);
    }

    /**
     * Saves a prescription
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePrescription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:patients,id',
            'complaints' => 'required_without:diagnosis|max:150',
            'diagnosis' => 'required_without:complaints|max:150',
            'investigations' => 'max:150',
            'remarks' => 'max:150',
            'prescribedDrugs' => 'array',
            'prescribedDrugs.*.drug' => 'required_with:prescribedDrugs',
            'prescribedDrugs.*.dose' => 'required_with:prescribedDrugs',
            'pharmacyDrugs' => 'array',
            'pharmacyDrugs.*.name' => 'required_with:pharmacyDrugs',
            'pharmacyDrugs.*.remarks' => 'max:200',
        ]);

        $patient = Patient::find($request->id);
        if (Gate::denies('prescribeMedicine', $patient)) {
            return response()->json(['status' => 0, 'message' => 'Unauthorized action'], 403);
        }

        // At least one of the complaints or diagnosis has to be present
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'errors' => $validator->errors()], 422);
        }

        $prescription = new Prescription();

        DB::beginTransaction();
        try {
            $prescription->complaints = $request->complaints;
            $prescription->investigations = $request->investigations;
            $prescription->diagnosis = $request->diagnosis;
            $prescription->remarks = $request->remarks ?: "";
            $prescription->creator()->associate(User::getCurrentUser());
            $prescription->patient()->associate($patient);
            $prescription->save();

            // Save the prescribed drugs
            foreach ($request->prescribedDrugs as $prescribedDrug) {
                $drug = new PrescriptionDrug();
                $drug->dosage()->associate(Dosage::find($prescribedDrug['dose']['id']));
                $drug->frequency()->associate(DosageFrequency::find($prescribedDrug['frequency']['id']));
                $drug->period()->associate(DosagePeriod::find($prescribedDrug['period']['id']));
                $drug->drug()->associate(Drug::find($prescribedDrug['drug']['id']));
                $prescription->prescriptionDrugs()->save($drug);
            }

            // Save the pharmacy drugs
            foreach ($request->pharmacyDrugs as $pharmacyDrug) {
                $drug = new PrescriptionPharmacyDrug();
                $drug->drug = $pharmacyDrug['name'];
                $drug->remarks = $pharmacyDrug['remarks'] ?? "";
                $prescription->prescriptionPharmacyDrugs()->save($drug);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();

            return response()->json(['status' => 0, 'message' => $e->getMessage()], 500);
        }
        DB::commit();

        return response()->json(['status' => 1, 'prescriptionId' => $prescription->id], 200);
    }

    /**
     * Get the prescriptions of a given patient
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPrescriptions($id)
    {
        $patient = Patient::find($id);
        if (Gate::denies('viewPrescriptions', $patient)) {
            return response()->json(['status' => 0, 'message' => 'Unauthorized action'], 403);
        }

        $prescriptions = $patient->prescriptions()->where('issued', false)
            ->with([
                'prescriptionDrugs.dosage',
                'prescriptionDrugs.frequency',
                'prescriptionPharmacyDrugs',
                'prescriptionDrugs.period',
                'prescriptionDrugs.drug.quantityType'
            ])->get();

        return response()->json(['prescriptions' => $prescriptions, 'status' => 1]);
    }

    /**
     * Get the prescriptions to be issued of the clinic.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllRemainingPrescriptions()
    {
        if (Gate::denies('issueMedicine', Patient::class)) {
            return response()->json(['status' => 0, 'message' => 'Unauthorized action'], 403);
        }

        $clinic = Clinic::getCurrentClinic();
        $prescriptions = Prescription::whereIn('patient_id', $clinic->patients()->pluck('id'))
            ->where('issued', false)->orderBy('id')
            ->with([
                'prescriptionDrugs.dosage',
                'prescriptionDrugs.frequency',
                'prescriptionPharmacyDrugs',
                'prescriptionDrugs.period',
                'prescriptionDrugs.drug.quantityType',
                'patient'
            ])->get();

        return response()->json(['prescriptions' => $prescriptions, 'status' => 1]);
    }

    /**
     * Check the availability of stocks
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStocksAvailability(Request $request)
    {
        $clinic = Clinic::getCurrentClinic();
        $stocks = $clinic->drugs()->whereIn('id', $request->data['drugs'])->select(['id', 'quantity'])->get();

        return response()->json([
            'prescriptionId' => $request->data['prescriptionId'],
            'stocks' => $stocks,
            'status' => 1
        ]);
    }

    /**
     * Issue a prescription.
     * Mark prescription as issued. Then register the payment.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function issuePrescription(Request $request)
    {
        $prescription = Prescription::find($request->prescription['id']);
        if (empty($prescription) || Gate::denies('issuePrescription', $prescription)) {
            return response()->json(['status' => 0, 'message' => 'Unauthorized action'], 403);
        }

        $validator = Validator::make($request->all(), [
            'prescription' => 'required|array',
            'prescription.id' => 'required|exists:prescriptions,id',
            'prescription.payment' => 'required|numeric',
            'prescription.prescription_drugs' => 'array',
            'prescription.prescription_drugs.*.issuedQuantity' => 'numeric',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json(['status' => 0, 'message' => $errors[0]], 422);
        }

        if ($prescription->issued) {
            return response()->json(['status' => -1, 'message' => 'Prescription is already issued'], 200);
        }

        if ($prescription->prescriptionDrugs()->count() != count($request->prescription['prescription_drugs'])) {
            return response()->json(['status' => 0, 'message' => 'Drugs data are incomplete'], 422);
        }

        DB::beginTransaction();
        try {
            $prescription->issued = true;
            $prescription->issued_by()->associate(User::getCurrentUser());
            $prescription->issued_at = Carbon::now();
            $prescription->save();

            foreach ($request->prescription['prescription_drugs'] as $item) {
                $drug = PrescriptionDrug::find($item['id']);
                $drug->issuedQuantity = $item['issuedQuantity'];
                $drug->save();

                $inventory = $drug->drug;
                $inventory->quantity -= $drug->issuedQuantity;
                $inventory->save();
            }

            // Create a payment
            $payment = new Payment();
            $payment->clinic()->associate(Clinic::getCurrentClinic());
            $payment->patient()->associate($prescription->patient);
            $payment->amount = $request->prescription['payment'];
            $payment->payment_date = Carbon::now();
            $payment->created_by()->associate(User::getCurrentUser());
            $payment->save();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();

            return response()->json(['status' => 0, 'message' => 'Error occurred'], 500);
        }

        DB::commit();

        return response()->json(['status' => 1]);
    }

    /**
     * Get the queue of patients for today.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatientsQueue()
    {
        $clinic = Clinic::getCurrentClinic();
        $patients = Queue::where('clinic_id', $clinic->id)
            ->whereDate('created_at', Carbon::today())
            ->with(['patient'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->pluck('patient');

        return response()->json(['patients' => $patients, 'status' => 1]);
    }

    /**
     * Get the patient's prescriptions' histories
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPrescriptionsHistory($id)
    {
        $patient = Patient::find($id);
        if (Gate::denies('viewPrescriptionsHistory', $patient)) {
            return response()->json(['status' => 0, 'message' => 'Unauthorized action'], 403);
        }

        $prescriptions = $patient->prescriptions()->where('issued', true)
            ->orderBy('id', 'desc')
            ->with([
                'prescriptionDrugs.dosage',
                'prescriptionDrugs.frequency',
                'prescriptionPharmacyDrugs',
                'prescriptionDrugs.period',
                'prescriptionDrugs.drug.quantityType'
            ])->get();

        return response()->json(['prescriptions' => $prescriptions, 'status' => 1]);
    }
}
