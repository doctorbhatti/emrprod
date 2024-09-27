<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\DrugType;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;
use Illuminate\Http\Request;

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
            // Fetch unique notifications (based on the message) for the admin
            $notifications = Notification::with('clinics')
                ->select('id', 'message', 'created_at') // Only select necessary fields
                ->whereIn('id', function ($query) {
                    $query->select(DB::raw('MIN(id)')) // Select the minimum ID for each unique message
                        ->from('notifications')
                        ->groupBy('message'); // Group by message only
                })
                ->orderBy('created_at', 'desc')
                ->distinct() // Ensure distinct messages
                ->paginate(10);

            Log::info('Fetched notifications:', $notifications->toArray());




            return view("admin.acceptClinics", compact("clinics", "acceptedClinics", "notifications"));
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
                $m->to($clinic->email, $clinic->name)->subject('Healthy Life Clinic | EMR Systems - Clinic Accepted');
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
        try {
            // Find the clinic by ID
            $clinic = Clinic::find($id);

            // Check if the clinic exists
            if (!$clinic) {
                return back()->with('error', 'Clinic not found.');
            }

            // Delete all related models (cascade delete)

            // Delete users
            $clinic->users()->delete();

            // Delete patients and their related prescriptions
            $clinic->patients()->each(function ($patient) {
                $patient->prescriptions()->delete(); // Delete prescriptions related to the patient
                $patient->delete(); // Delete the patient itself
            });

            // Delete drugs
            $clinic->drugs()->delete();

            // Delete drug types
            $clinic->quantityTypes()->delete(); // Assuming 'quantityTypes' are drug types

            // Delete queues
            $clinic->queues()->delete();

            // Delete dosages
            $clinic->dosages()->delete();

            // Delete dosage frequencies
            $clinic->dosageFrequencies()->delete();

            // Delete dosage periods
            $clinic->dosagePeriods()->delete();

            // Finally, delete the clinic itself
            $clinic->delete();

            return back()->with('success', $clinic->name . ' clinic and all related data removed successfully.');

        } catch (\Exception $e) {
            // Handle any errors that might occur during the deletion process
            return back()->with('error', 'Failed to remove clinic. ' . $e->getMessage());
        }
    }
    /**
     * Sends a notification message to all accepted and not-held clinics.
     */
    public function sendNotificationToClinics(Request $request)
    {
        $message = $request->input('message');

        if (!$message) {
            return back()->with('error', 'Message cannot be empty.');
        }

        try {
            $clinics = Clinic::where('accepted', true)->where('is_held', false)->get();

            foreach ($clinics as $clinic) {
                Notification::create([
                    'clinic_id' => $clinic->id,
                    'message' => $message,
                    'read_status' => false,
                ]);
            }

            return back()->with('success', 'Notification sent to all eligible clinics.');
        } catch (Exception $e) {
            Log::error("Failed to send notifications: " . $e->getMessage());
            return back()->with('error', 'Failed to send notifications.');
        }
    }

    /**
     * Fetches all notifications for the clinic.
     */
    public function fetchNotifications()
    {
        $clinic = Clinic::getCurrentClinic();

        $notifications = Notification::where('clinic_id', $clinic->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notifications);
    }

    /**
     * Marks a specific notification as read.
     */
    public function markNotificationAsRead($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->read_status = true;
            $notification->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Displays all notifications in a table format.
     */
    public function viewAllNotifications()
    {
        $clinic = Clinic::getCurrentClinic();
        $notifications = Notification::where('clinic_id', $clinic->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('clinic.notifications.index', compact('notifications'));
    }

    public function getNotification($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found.'], 404);
        }

        return response()->json($notification);
    }

    // Assuming you're in the method for the notification history view
    // public function showNotificationHistory()
    // {
    //     // Fetch notifications with associated clinics
    //     $notifications = Notification::with('clinic')->paginate(10);

    //     // Pass notifications to the view
    //     return view('acceptclinic', compact('notifications'));
    // }


    public function deleteNotificationsByMessage(Request $request)
    {
        $message = $request->input('message');

        // Delete all notifications with the same message
        Notification::where('message', $message)->delete();

        return redirect()->back()->with('success', 'All notifications with the same message have been deleted.');
    }
    public function deleteNotification($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }

}