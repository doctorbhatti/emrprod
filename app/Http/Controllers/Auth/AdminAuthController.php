<?php

namespace App\Http\Controllers\Auth;

use App\Models\User; // Ensure you're using the correct User model path
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; // Update Validator namespace
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminAuthController extends Controller
{
    use AuthenticatesUsers, ThrottlesLogins;

    /**
     * To authenticate with username
     *
     * @var string
     */
    protected $username = "username";

    /**
     * Guard for the Admin Login
     *
     * @var string
     */
    protected $guard = "admin";

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = 'Admin/admin';

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
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);

        if (Auth::guard($this->guard)->attempt($credentials, $request->filled('remember'))) {
            return $this->sendLoginResponse($request);
        }

        if (method_exists($this, 'incrementLoginAttempts')) {
            $this->incrementLoginAttempts($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard($this->guard);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();

        return redirect()->route('admin.login');
    }

    public function postLogin(Request $request)
    {
        // Validate the incoming login request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check the credentials and attempt login
        $credentials = $request->only('username', 'password');

        if (Auth::guard($this->guard)->attempt($credentials, $request->filled('remember'))) {
            // Authentication passed, redirect to the 'admin.acceptClinics' route
            return redirect()->route('admin.acceptClinics');
        }

        // Authentication failed, redirect back with an error message
        return redirect()->back()->withErrors([
            'login' => 'Invalid username or password',
        ])->withInput($request->except('password'));
    }



    public function getLogin()
    {
        // Check if the admin is already logged in
        if (Auth::guard($this->guard)->check()) {
            return redirect()->intended($this->redirectTo); // Redirect if already logged in
        }

        return view('admin.adminLogin'); // Replace with the correct path to your login view
    }

}
