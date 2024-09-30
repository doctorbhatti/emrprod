<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    const LOG_CLASS_NAME = "FeedbackController: ";

    /**
     * Display the feedback form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFeedbackForm()
    {
        return view('feedback.feedback');
    }

    /**
     * Handle and send feedback via email.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendFeedback(Request $request)
    {
        Log::debug(self::LOG_CLASS_NAME . "Sending feedback: " . $request->feedback);

        // Validate feedback and attachment
        $validator = Validator::make($request->all(), [
            'feedback' => 'required|string|min:20|max:200',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_CLASS_NAME . "Validation errors: ", $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        $user = User::getCurrentUser();
        $emails = config('mail.contact_us_address', 'healthylifeclinicemr@gmail.com');
        $emails = explode(',', $emails);

        // Handle file upload for attachment
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('feedback_attachments', 'public');
        }

        try {
            // Send the email
            Mail::send('emails.feedback', [
                'feedback' => $request->feedback,
                'user' => $user,
                'attachmentPath' => $attachmentPath // Pass the attachment path
            ], function ($message) use ($emails, $attachmentPath) {
                $message->to($emails)->subject('HLC | EMR - User Feedback');

                // Attach the file if uploaded
                if ($attachmentPath) {
                    $message->attach(Storage::disk('public')->path($attachmentPath));
                }
            });

            Log::debug(self::LOG_CLASS_NAME . "Complaint sent by user ID: " . $user->id);
            return back()->with('success', "Ticket submitted successfully. We will get back to you shortly!");
        } catch (\Exception $e) {
            Log::error(self::LOG_CLASS_NAME . "Failed to open ticket: " . $e->getMessage());
            return back()->with('error', 'Failed to open ticket. Please try again later.');
        }
    }
}
