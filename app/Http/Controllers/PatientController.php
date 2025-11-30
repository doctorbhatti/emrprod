<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Exceptions\NotFoundException;
use App\Lib\Utils;
use App\Models\Patient;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    /**
     * Display the list of patients.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPatientList()
    {
        $clinic = Clinic::getCurrentClinic();
        return view('patients.patients', ['patients' => []]);
    }

    /**
     * Get the list of patients for DataTables with server-side processing.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listPatients(Request $request)
    {
        $clinic = Clinic::getCurrentClinic();

        $draw = $request->query('draw');
        $start = $request->query('start');
        $length = $request->query('length');
        $search = $request->query('search');
        $order = $request->query('order');
        $query = $search['value'];
        $orderByColIdx = $order[0]["column"];
        $orderByDirection = $order[0]["dir"];

        $orderByCol = 'first_name';
        switch ($orderByColIdx) {
            case 2:
                $orderByCol = 'phone';
                break;
            case 3:
                $orderByCol = 'address';
                break;
            case 4:
                $orderByCol = 'dob';
                $orderByDirection = $orderByDirection === 'asc' ? 'desc' : 'asc';
                break;
        }

        Log::debug("Draw: $draw, Start: $start, Length: $length, OrderByColumn: $orderByColIdx, Query: $query");

        $totalRecords = $clinic->patients()->count();
        $filteredRecords = $totalRecords;

        $patients = $clinic->patients();

        if (!empty($query)) {
            $patients = $patients->where(DB::raw('concat(first_name, " ", last_name)'), 'like', "%$query%");
            $filteredRecords = $patients->count();
        }

        $patients = $patients->orderBy($orderByCol, $orderByDirection)
            ->skip($start)->take($length)->get();

        $data = $patients->map(function ($patient) {
            $buttons = '';
            if (\Gate::allows('delete', $patient)) {
                $buttons = "
                    <button class=\"btn btn-sm btn-danger\" data-bs-toggle=\"modal\"
                        data-bs-target=\"#confirmDeletePatientModal\"
                        onclick=\"showConfirmDelete($patient->id,'$patient->first_name $patient->last_name')\">
                        <i class=\"fa fa-recycle fa-lg\" data-bs-toggle=\"tooltip\"
                        data-placement=\"bottom\" title=\"Delete this patient?\"
                        data-original-title=\"Delete this patient? You won't be able to delete this patient if the patient has any records associated to him/her in the system.\"></i>
                    </button>";
            }

            return [
                $patient->id,
                $patient->first_name . ' ' . $patient->last_name,
                $patient->phone,
                $patient->address,
                Utils::getAge($patient->dob),
                $buttons
            ];
        });

        $result = [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ];

        return response()->json($result);
    }

    /**
     * Add a new patient.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPatient(Request $request)
    {
        $this->authorize('add', Patient::class);

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'gender' => 'required|in:Male,Female',
            'nic' => 'nullable|regex:/[0-9]{9}[vV]/',
            'bloodGroup' => 'required|in:A +,A -,B +,B -,AB +,AB -,O +,O -,N/A',
            'dob' => 'nullable|date|date_format:Y/m/d|before:today|after:' . now()->subYears(150)->format('Y/m/d'),
        ]);

        if ($validator->fails()) {
            Log::error('Validation errors:', $validator->errors()->toArray());
            return back()->with('type', 'patient')->withErrors($validator)->withInput();
        }

        $clinic = Clinic::getCurrentClinic();

        if (!empty($request->nic) && $clinic->patients()->where('nic', $request->nic)->exists()) {
            $validator->getMessageBag()->add('nic', 'A patient with this NIC already exists.');
            Log::error('Validation errors:', $validator->errors()->toArray());
            return back()->with('type', 'patient')->withErrors($validator)->withInput();
        }

        $patient = new Patient();
        $patient->fill([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'dob' => $request->dob ? date('Y-m-d', strtotime($request->dob)) : null,
            'gender' => $request->gender,
            'address' => $request->address,
            'nic' => $request->nic,
            'phone' => $request->phone,
            'blood_group' => $request->bloodGroup,
            'allergies' => $request->allergies,
            'family_history' => $request->familyHistory,
            'medical_history' => $request->medicalHistory,
            'post_surgical_history' => $request->postSurgicalHistory,
            'remarks' => $request->remarks,
        ]);

        try {
            $patient->creator()->associate(Auth::user());
            $patient->clinic()->associate($clinic);
            $patient->save();
        } catch (Exception $e) {
            Log::error('Failed to save patient:', ['error' => $e->getMessage()]);
            $validator->getMessageBag()->add('general', 'Unable to save the patient details. A patient with similar details might already exist.');
            return back()->with('type', 'patient')->withErrors($validator)->withInput();
        }

        return back()->with([
            'success' => $request->firstName . ' added successfully',
            'patient_id' => $patient->id
        ]);
    }

    /**
     * Edit a patient.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws NotFoundException
     */
    public function editPatient($id, Request $request)
    {
        $patient = Patient::findOrFail($id);
        $this->authorize('edit', $patient);

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'gender' => 'required|in:Male,Female',
            'nic' => 'nullable|regex:/[0-9]{9}[vV]/',
            'bloodGroup' => 'required|in:A +,A -,B +,B -,AB +,AB -,O +,O -,N/A',
            'dob' => 'nullable|date|date_format:Y/m/d|before:today|after:' . now()->subYears(150)->format('Y/m/d'),
        ]);

        if ($validator->fails()) {
            return back()->with('type', 'patient')->withErrors($validator)->withInput();
        }

        $clinic = Clinic::getCurrentClinic();

        if (!empty($request->nic) && $clinic->patients()->where('nic', $request->nic)->where('id', '<>', $id)->exists()) {
            $validator->getMessageBag()->add('nic', 'A patient with this NIC already exists.');
            return back()->with('type', 'patient')->withErrors($validator)->withInput();
        }

        $patient->fill([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'dob' => $request->dob ? date('Y-m-d', strtotime($request->dob)) : null,
            'gender' => $request->gender,
            'address' => $request->address,
            'nic' => $request->nic,
            'phone' => $request->phone,
            'blood_group' => $request->bloodGroup,
            'allergies' => $request->allergies,
            'family_history' => $request->familyHistory,
            'medical_history' => $request->medicalHistory,
            'post_surgical_history' => $request->postSurgicalHistory,
            'remarks' => $request->remarks,
        ]);

        try {
            $patient->save();
        } catch (Exception $e) {
            Log::error('Failed to update patient:', ['error' => $e->getMessage()]);
            $validator->getMessageBag()->add('general', 'Unable to save the patient details.');
            return back()->with('type', 'patient')->withErrors($validator)->withInput();
        }

        return back()->with('success', 'Updated successfully');
    }

    /**
     * Display a specific patient.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws NotFoundException
     */
    public function getPatient($id)
    {
        $patient = Patient::findOrFail($id);
        $this->authorize('view', $patient);

        return view('patients.patient', ['patient' => $patient]);
    }

    /**
     * Delete a patient.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws NotFoundException
     */
    public function deletePatient($id)
    {
        $patient = Patient::findOrFail($id);
        $this->authorize('delete', $patient);

        DB::beginTransaction();
        try {
            $patient->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Failed to delete patient:', ['error' => $e->getMessage()]);
            return back()->with('error', "Unable to delete " . $patient->first_name);
        }

        return back()->with('success', $patient->first_name . " successfully removed");
    }

    /**
     * Display print preview for a patient.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPrintPreview($id)
    {
        $patient = Patient::findOrFail($id);
        return view('patients.printIDPreview', ['patient' => $patient]);
    }
}
