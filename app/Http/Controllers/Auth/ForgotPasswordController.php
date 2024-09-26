<?php
namespace App\Http\Controllers\Auth;

use App\Models\Clinic;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Http\Controllers\Controller;
use DB;
use Hash;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Mail;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    protected $redirectTo = "login";

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.passwords.reset', ['token' => $token, 'email' => $request->email]);
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => ['required', 'email', 'exists:clinics,email'],
        ]);

        // Get the clinic associated with the email
        $clinic = Clinic::where('email', $request->email)->first();

        // Check if the clinic has an admin user
        $adminUser = $clinic->users()->where('role_id', 1)->first(); // Assuming a relationship exists

        if (!$adminUser) {
            throw ValidationException::withMessages(['email' => ['No admin user found for this clinic.']]);
        }

        $token = Password::broker()->createToken($adminUser); // Generate the token
        \Log::info('Sending password reset email to: ' . $clinic->email);

        // Send the password reset link using the notification
        // Test sending email directly
        Mail::send('auth.emails.password', ['token' => $token], function ($message) use ($clinic) {
            $message->to($clinic->email)
                    ->subject('Password Reset Request');
        });

        return back()->with('status', __('If your email is in our records, we have sent you a password reset link.'));
    }

    public function reset(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|exists:clinics,email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        // Find the clinic associated with the email
        $clinic = Clinic::where('email', $request->email)->first();

        if (!$clinic) {
            \Log::warning('Clinic not found for email: ' . $request->email);
            return back()->withErrors(['email' => 'Clinic not found.']);
        }

        // Find the admin user for the clinic
        $user = User::where('clinic_id', $clinic->id)
            ->where('role_id', 1)
            ->first();

        if (!$user) {
            \Log::warning('No admin user found for clinic ID: ' . $clinic->id);
            return back()->withErrors(['email' => 'No admin user found for this clinic.']);
        }

        // Check for the token
        $passwordReset = DB::table('password_resets')->where('email', $request->email)->first();

        if (!$passwordReset) {
            \Log::warning('No token found for email: ' . $request->email);
            return back()->withErrors(['token' => 'No valid token found. Please reapply for a password reset.']);
        }

        // Validate the token
        $validToken = $this->isValidToken($request->token, $request->email);

        if (!$validToken) {
            \Log::warning('Invalid or expired token for email: ' . $request->email);
            return back()->withErrors(['token' => 'Invalid or expired token. Please reapply for a password reset.']);
        }

        // Reset the password directly
        $user->password = bcrypt($request->password); // Hash the new password
        $user->save(); // Save the changes

        // Optionally, delete the token from the password_resets table after a successful reset
        DB::table('password_resets')->where('email', $request->email)->delete();

        \Log::info('Password reset successfully for email: ' . $request->email);

        // Redirect with success message
        return redirect($this->redirectTo)->with('status', 'Password has been reset successfully. Please log in.');
    }

    protected function isValidToken($token, $email)
    {
        // Check if the token exists
        $passwordReset = DB::table('password_resets')
            ->where('email', $email)
            ->first();

        if ($passwordReset) {
            // Check if the hashed token matches the provided token
            if (
                Hash::check($token, $passwordReset->token) &&
                $passwordReset->created_at >= now()->subMinutes(60)
            ) {
                \Log::info('Valid token found for email: ' . $email);
                return true;
            }
        }

        \Log::warning('No valid token found for email: ' . $email);
        return false; // Return false if no valid token found
    }




    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();
    }
}
