<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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

        $validator = Validator::make($request->all(), [
            'feedback' => 'required|string|min:20|max:200',
        ]);

        if ($validator->fails()) {
            Log::error(self::LOG_CLASS_NAME . "Validation errors: ", $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        $user = User::getCurrentUser();
        $emails = config('mail.contact_us_address', 'chr24x7@gmail.com');
        $emails = explode(',', $emails);

        try {
            Mail::send('emails.feedback', ['feedback' => $request->feedback, 'user' => $user], function ($message) use ($emails) {
                $message->to($emails)->subject('CHR247.COM - User Feedback');
            });

            Log::debug(self::LOG_CLASS_NAME . "Feedback sent by user ID: " . $user->id);
            return back()->with('success', "Feedback submitted successfully. Thank you for your feedback!");
        } catch (\Exception $e) {
            Log::error(self::LOG_CLASS_NAME . "Failed to send feedback: " . $e->getMessage());
            return back()->with('error', 'Failed to submit feedback. Please try again later.');
        }
    }
}
