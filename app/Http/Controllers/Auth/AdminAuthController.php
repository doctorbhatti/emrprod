<?php

namespace App\Http\Controllers\Auth;

use App\Models\Admin;
use App\Models\User; // Update to your actual Admin model if different
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Log; // Use the Log facade for debugging

class AdminAuthController extends Controller
{
    use AuthenticatesUsers, ThrottlesLogins;

    /**
     * To authenticate with username.
     *
     * @var string
     */
    protected $username = 'username';

    /**
     * Login view.
     *
     * @var string
     */
    protected $loginView = 'admin.adminLogin';

    /**
     * Guard for the Admin Login.
     *
     * @var string
     */
    protected $guard = 'admin';

    /**
     * Where to redirect users after login/registration.
     *
     * @var string
     */
    protected $redirectTo = 'Admin/admin';

    /**
     * After Logout.
     *
     * @var string
     */
    protected $redirectAfterLogout = 'Admin/login';

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Show the admin login form.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getLogin()
    {
        // Check if the admin is already logged in
        if (Auth::guard($this->guard)->check()) {
            Log::info('Admin already logged in, redirecting to intended page.');
            return redirect()->intended($this->redirectTo);
        }

        Log::info('Rendering admin login view.');
        return view($this->loginView);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        Log::info('Attempting admin login', ['username' => $credentials['username']]);

        // Attempt to log the admin in with the specified guard
        if (Auth::guard($this->guard)->attempt($credentials, $request->filled('remember'))) {
            Log::info('Admin logged in successfully, redirecting to dashboard.');

            // Use Laravel's sendLoginResponse to handle redirection properly
            return $this->sendLoginResponse($request);
        }

        Log::warning('Admin login failed, invalid credentials.');

        // Redirect back with an error message on failure
        return redirect()->back()->withInput($request->only('username', 'remember'))
            ->withErrors(['general' => 'Invalid credentials.']);
    }

    /**
     * Handle a login request using Laravel's built-in throttle and validation methods.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validate login request
        $this->validateLogin($request);

        $throttles = method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request);

        if ($throttles) {
            Log::warning('Too many login attempts detected, locking out the admin.');
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        Log::info('Attempting to log in admin with credentials.', $credentials);

        // Attempt to log the admin in with the specified guard
        if (Auth::guard($this->guard)->attempt($credentials, $request->filled('remember'))) {
            Log::info('Admin successfully authenticated.');
            return $this->sendLoginResponse($request); // Use the standard response handler
        }

        if ($throttles) {
            Log::info('Incrementing login attempts counter.');
            $this->incrementLoginAttempts($request);
        }

        Log::warning('Failed login attempt, invalid credentials provided.');

        // Send the failed login response
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Handle a logout request to the application.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::guard($this->guard)->logout();
        // Clear all session data
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        Log::info('Admin logged out successfully.');

        // Redirect to login page after logout
        return redirect($this->redirectAfterLogout)->with('success', 'You have been logged out.');
    }


    /**
     * Handle the GET request for logging out.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogout()
    {
        return $this->logout(); // Call the logout method
    }

    /**
     * Override the sendLoginResponse to ensure correct redirection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        Log::info('Login response sent, redirecting to: ' . $this->redirectTo);

        return redirect()->intended($this->redirectTo);
    }
}

