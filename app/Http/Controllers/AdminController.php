<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\DrugType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class AdminController extends Controller
{
    private $guard = "admin";

    /**
     * Handles the root URL under Admin route group.
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (\Auth::guard($this->guard)->check()) {
            // Log to confirm the user is authenticated
            Log::info('Admin is authenticated.');

            $clinics = Clinic::where('accepted', false)->get();
            $acceptedClinics = Clinic::where('accepted', true)->get();
            return view("admin.acceptClinics", compact("clinics", "acceptedClinics"));
        }

        Log::info('Admin is not authenticated. Redirecting to login.');
        return view("admin.adminLogin");
    }

    /**
     * Accepts a clinic, Adds the basic quantity types to the clinic.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptClinic($id)
    {
        $clinic = Clinic::find($id);
        DB::beginTransaction();
        try {
            $clinic->accepted = true;
            $clinic->save();

            // When accepting a clinic, basic drug types are also added along with that.
            $quantityTypes = ['Pills', 'Litres', 'Tablets', 'Milli Litres', 'Bottles'];
            $types = [];
            $user = $clinic->users()->first();

            foreach ($quantityTypes as $quantityType) {
                $type = new DrugType();
                $type->drug_type = $quantityType;
                $type->created_by = $user->id;
                $types[] = $type;
            }

            $clinic->quantityTypes()->saveMany($types);

            Mail::send('auth.emails.clinicAccepted', ['clinic' => $clinic], function ($m) use ($clinic) {
                $m->to($clinic->email, $clinic->name)->subject('chr247.com - Clinic Accepted');
            });
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Unable to accept clinic due to: " . $e->getMessage());
            return back()->with("error", "Unable to accept the clinic");
        }
        DB::commit();
        return back()->with("success", $clinic->name . " clinic Accepted");
    }

    /**
     * Temporarily holds a clinic, preventing login until unheld.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function holdClinic($id)
    {
        $clinic = Clinic::find($id);

        if (!$clinic || $clinic->accepted == false) {
            return back()->with("error", "Clinic not found or not accepted.");
        }

        try {
            $clinic->is_held = true; // Add a column `is_held` in the Clinic model
            $clinic->save();
        } catch (Exception $e) {
            Log::error("Unable to hold clinic: " . $e->getMessage());
            return back()->with("error", "Failed to hold the clinic.");
        }

        return back()->with("success", "Clinic " . $clinic->name . " is now on hold.");
    }

    public function showHoldPage()
    {
        // You can pass any necessary data to the view if needed
        return view('admin.clinicHold'); // Make sure this view exists
    }

    /**
     * Removes the hold on a clinic, allowing normal login.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unholdClinic($id)
    {
        $clinic = Clinic::find($id);

        if (!$clinic || $clinic->accepted == false) {
            return back()->with("error", "Clinic not found or not accepted.");
        }

        try {
            $clinic->is_held = false; // Remove hold status
            $clinic->save();
        } catch (Exception $e) {
            Log::error("Unable to unhold clinic: " . $e->getMessage());
            return back()->with("error", "Failed to unhold the clinic.");
        }

        return back()->with("success", "Clinic " . $clinic->name . " is now active.");
    }

    /**
     * Remove a clinic which is to be accepted.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteClinic($id)
    {
        $clinic = Clinic::find($id);

        // Check if the clinic exists
        if (!$clinic) {
            return back()->with('error', 'Clinic not found.');
        }

        // Delete associated users before deleting the clinic
        $clinic->users()->delete();

        // Delete the clinic
        $clinic->delete();

        return back()->with('success', $clinic->name . ' clinic removed');
    }

}
