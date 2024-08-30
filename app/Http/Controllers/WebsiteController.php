<?php

namespace App\Http\Controllers;

use App\Lib\Logger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Exception;

class WebsiteController extends Controller
{
    const LOG_CLASS_NAME = "WebsiteController: ";

    /**
     * Display the About Us page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAboutUsPage()
    {
        return view("website.aboutUs");
    }

    /**
     * Display the Features page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFeaturesPage()
    {
        return view("website.features");
    }

    /**
     * Display the Privacy Policy page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPrivacyPolicyPage()
    {
        return view("website.privacyPolicy");
    }

    /**
     * Display the Contact Us page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContactUs()
    {
        return view("website.contactUs");
    }

    /**
     * Handle the submission of a contact us message.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postContactUs(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:50',
            'contact' => 'required|min:2|max:50',
            'email' => 'required|email|min:2|max:50',
            'message' => 'required|min:2|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->only(['name', 'contact', 'email', 'message']);

        try {
            // Get the contact email addresses from the environment configuration
            $contactEmails = explode(',', env('CONTACTUS_MAIL', 'chr24x7@gmail.com'));

            // Send the contact us email
            Mail::send('emails.contactUs', ['msg' => $data], function ($message) use ($contactEmails) {
                $message->to($contactEmails)
                    ->subject('chr247.com - New Contact Us Message');
            });

            Log::debug(self::LOG_CLASS_NAME . "Contact us email sent successfully", $data);

        } catch (Exception $e) {
            Log::error(self::LOG_CLASS_NAME . "Error sending contact us message: " . $e->getMessage(), [
                'failures' => Mail::failures()
            ]);
            return back()->with('error', 'There was an error sending your message. Please try again later.');
        }

        return back()->with('success', "Your message was submitted successfully. We will contact you soon.");
    }
}
