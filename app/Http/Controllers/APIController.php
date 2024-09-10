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
use Illuminate\Http\JsonResponse;
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
        $validator = \Validator::make($request->all(), [
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
            'pharmacyDrugs.*.remarks' => 'max:200'
        ]);

        $patient = Patient::find($request->id);
        if (Gate::denies('prescribeMedicine', $patient)) {
            return response()->json(['status' => 0, 'message' => 'Unauthorized action'], 404);
        }

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $prescription = new Prescription();
            $prescription->complaints = $request->input('complaints', '');
            $prescription->investigations = $request->input('investigations', '');
            $prescription->diagnosis = $request->input('diagnosis', '');
            $prescription->remarks = $request->input('remarks', '');
            $prescription->issued_at = now(); // Set issued_at to current timestamp
            $prescription->creator()->associate(User::getCurrentUser());
            $prescription->patient()->associate($patient);
            $prescription->save();
            // Save the prescribed drugs
            if (is_array($request->prescribedDrugs)) {
                foreach ($request->prescribedDrugs as $prescribedDrug) {
                    // Log the $prescribedDrug to see its structure
                    Log::info('Processing prescribed drug: ', $prescribedDrug);

                    // Check if the required fields are present
                    if (isset($prescribedDrug['dose']['id'], $prescribedDrug['drug']['id'])) {
                        // Find the required related models
                        $dosage = Dosage::find($prescribedDrug['dose']['id']);
                        $drug = Drug::find($prescribedDrug['drug']['id']);

                        // Optional related models
                        $frequency = isset($prescribedDrug['frequency']['id']) ? DosageFrequency::find($prescribedDrug['frequency']['id']) : null;
                        $period = isset($prescribedDrug['period']['id']) ? DosagePeriod::find($prescribedDrug['period']['id']) : null;

                        // Check if the required related models are found
                        if (!$dosage || !$drug) {
                            Log::error('Invalid dosage or drug ID', [
                                'dosage' => $dosage,
                                'drug' => $drug,
                            ]);
                            continue; // Skip this prescribed drug and continue with the next
                        }

                        // Create a new PrescriptionDrug and associate the models
                        $prescriptionDrug = new PrescriptionDrug();
                        $prescriptionDrug->dosage()->associate($dosage);
                        $prescriptionDrug->drug()->associate($drug);

                        // Associate optional models if they exist
                        if ($frequency) {
                            $prescriptionDrug->frequency()->associate($frequency);
                        }
                        if ($period) {
                            $prescriptionDrug->period()->associate($period);
                        }

                        // Save the prescription drug
                        $prescription->prescriptionDrugs()->save($prescriptionDrug);
                        Log::info('Prescribed drug saved successfully.');
                    } else {
                        Log::warning('Missing required keys in prescribed drug data', $prescribedDrug);
                    }
                }
            } else {
                Log::warning('No prescribed drugs to process or incorrect data format.');
            }


            // Save the pharmacy drugs
            if (is_array($request->pharmacyDrugs)) {
                foreach ($request->pharmacyDrugs as $pharmacyDrug) {
                    if (isset($pharmacyDrug['name'])) {
                        $drug = new PrescriptionPharmacyDrug();
                        $drug->drug = $pharmacyDrug['name'];
                        $drug->remarks = $pharmacyDrug['remarks'] ?? '';
                        $prescription->prescriptionPharmacyDrugs()->save($drug);
                    }
                }
            }

            DB::commit();
            return response()->json(['status' => 1, 'prescriptionId' => $prescription->id], 200);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return response()->json(['status' => 0, 'message' => 'Error saving prescription'], 500);
        }
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
    public function issuePrescription(Request $request) {
        $prescription = Prescription::find($request->prescription['id']);
        if (empty($prescription) || Gate::denies('issuePrescription', $prescription)) {
            return response()->json(['status' => 0], 404);
        }

        $validator = Validator::make($request->all(), [
            'prescription'                                     => 'required',
            'prescription.id'                                  => 'required',
            'prescription.payment'                             => 'required|numeric',
            'prescription.prescription_drugs'                  => 'array',
            'prescription.prescription_drugs.*.issuedQuantity' => 'numeric'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json(['status' => 0, 'message' => $errors[0]], 404);
        }
        if ($prescription->issued) {
            return response()->json(['status' => -1, 'message' => "Prescription is already issued"], 200);
        }
        if ($prescription->prescriptionDrugs()->count() != count($request->prescription['prescription_drugs'])) {
            return response()->json(['status' => -1, 'message' => "Invalid prescription"], 403);
        }

        DB::beginTransaction();
        try {
            //mark prescription as updated
            $prescription->issued    = true;
            $prescription->issued_at = new Carbon();
            $prescription->update();

            //save payment details
            $payment = new Payment();
            $payment->prescription()->associate($prescription);
            $payment->amount  = $request->prescription['payment'];
            $payment->remarks = $request->prescription['paymentRemarks'] ?? null;
            $payment->save();

            //save prescription drug quantities and decrease stocks
            foreach ($request->prescription['prescription_drugs'] as $prescription_drug) {
                //setting issued quantity of each drug in the prescription
                $prescriptionDrug           = $prescription->prescriptionDrugs()
                    ->where('id', $prescription_drug['id'])->first();
                $prescriptionDrug->quantity = $prescription_drug['issuedQuantity'];
                $prescriptionDrug->update();

                //decreasing stocks
                $drug           = $prescriptionDrug->drug;
                $quantityLeft   = $drug->quantity - $prescription_drug['issuedQuantity'];
                $drug->quantity = $quantityLeft >= 0 ? $quantityLeft : 0;
                $drug->update();
            }
        } catch (Exception $e) {
            DB::rollback();

            return response()->json(['status' => 0, 'message' => $e->getMessage()], 500);
        }
        DB::commit();

        return response()->json(['status' => 1]);
    }


    /**
     * Deletes a prescription.
     * Authorizes before deleting whether the user has permissions to delete the prescription.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function deletePrescription(int $id): JsonResponse
    {
        $prescription = Prescription::find($id);

        if (empty($prescription) || Gate::denies('deletePrescription', $prescription)) {
            return response()->json([
                'status' => 0,
                'message' => 'You are not authorized to delete prescriptions'
            ], 404);
        }

        if ($prescription->issued) {
            return response()->json([
                'status' => 0,
                'message' => 'The prescription is already issued. Therefore, it cannot be deleted'
            ], 500);
        }

        DB::beginTransaction();
        try {
            // Delete related prescription drugs and pharmacy drugs
            $prescription->prescriptionDrugs()->delete();
            $prescription->prescriptionPharmacyDrugs()->delete();

            $prescription->delete();

            DB::commit();

            return response()->json(['status' => 1]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();

            return response()->json(['status' => 0, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get the medical records of a patient.
     *
     * @param int $patientId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMedicalRecords($patientId)
    {
        // Fetch the patient or return a 404 if not found
        $patient = Patient::find($patientId);
        if (!$patient) {
            return response()->json(['status' => 0, 'message' => 'Patient not found'], 404);
        }

        // Authorization check
        if (Gate::denies('viewMedicalRecords', $patient)) {
            return response()->json(['status' => 0, 'message' => 'Unauthorized action'], 403);
        }

        // Fetch issued prescriptions with necessary relationships using eager loading
        $prescriptions = $patient->prescriptions()
            ->where('issued', true)
            ->orderBy('issued_at', 'asc')
            ->with([
                'prescriptionDrugs.dosage',
                'prescriptionDrugs.frequency',
                'prescriptionDrugs.period',
                'prescriptionDrugs.drug.quantityType',
                'prescriptionPharmacyDrugs',
                'payment'
            ])
            ->get();

        // Return the prescriptions and a success status
        return response()->json(['prescriptions' => $prescriptions, 'status' => 1]);
    }

    /**
     * Get the patients in the current queue
     *
     * @return JsonResponse
     */
    public function getQueue(): JsonResponse
    {
        $queue = Queue::getCurrentQueue();

        if (is_null($queue)) {
            return response()->json(['status' => 1, 'patients' => []]);
        }

        $patients = $queue->patients()
            ->withPivot(['id', 'inProgress'])
            ->wherePivot('completed', false)
            ->orderBy('pivot_inProgress', 'desc')
            ->orderBy('pivot_id')
            ->get();

        return response()->json(['status' => 1, 'patients' => $patients]);
    }

    /**
     * Update the current queue
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateQueue(Request $request): JsonResponse
    {
        // Validate incoming request
        $request->validate([
            'patient.id' => 'required|integer|exists:patients,id',
            'patient.pivot.inProgress' => 'required|integer|in:0,1,2',
        ]);

        $queue = Queue::getCurrentQueue();
        $patientId = $request->input('patient.id');
        $patient = Patient::findOrFail($patientId);

        $this->authorize('update', [$queue, $patient]);

        $patientPivot = $queue->patients()->wherePivot('completed', false)->find($patientId);

        if (is_null($patientPivot)) {
            return response()->json(['status' => 0, 'message' => "Patient not in the queue"]);
        }

        $inProgress = $request->input('patient.pivot.inProgress') === 1;
        $completed = $request->input('patient.pivot.inProgress') === 2;

        $patientPivot->pivot->update([
            'inProgress' => $inProgress,
            'completed' => $completed,
        ]);

        return response()->json(['status' => 1]);
    }
}
