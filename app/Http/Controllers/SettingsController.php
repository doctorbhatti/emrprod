<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Storage;
use View;

class SettingsController extends Controller
{


    public function __construct()
    {
        // Share sidebar data with all views
        View::share('sidebarData', $this->getSidebarData());
    }

    public function getSidebarData()
    {
        // Get the current clinic's name (assuming you are using the logged-in user's clinic)
        $clinic = Clinic::getCurrentClinic();
        $clinicName = $clinic ? $clinic->name : 'Clinic Name';

        // Get the current logo from settings
        $setting = Setting::first();
        $logo = $setting ? $setting->logo : 'default_logo.png'; // Default logo if none is set

        return [
            'clinicName' => $clinicName,
            'logo' => $logo,
        ];
    }
    /**
     * Shows the settings view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewSettings()
    {
        // Retrieve the current logo from the settings table
        $setting = Setting::first(); // Assuming there's only one row in the settings table
        $currentLogo = $setting ? $setting->logo : null; // Get the logo path or null if not set

        // Retrieve the authenticated user
        $user = Auth::user();

        // Pass the current logo and user data to the settings view
        return view('settings.settings', compact('currentLogo', 'user', 'setting'));
    }


    /**
     * Changes a user's password.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::getCurrentUser();

        if (Hash::check($request->current_password, $user->password)) {
            $user->password = bcrypt($request->password);
            $user->save();

            return back()->with('success', 'Password changed successfully.');
        }

        return back()->withInput()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    /**
     * Creates a new account.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createAccount(Request $request)
    {
        $this->authorize('register', User::class);

        $validator = Validator::make($request->all(), [
            'user_name' => 'required|max:255',
            'user_username' => 'required|unique:users,username',
            'user_role' => 'required|exists:roles,id|not_in:1', // Assuming ID 1 is Admin
            'user_password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->name = $request->user_name;
        $user->username = $request->user_username;
        $user->password = bcrypt($request->user_password);
        $user->role_id = $request->user_role; // Directly setting role ID
        $user->clinic_id = Clinic::getCurrentClinic()->id; // Directly setting clinic ID
        $user->save();

        return back()->with('success', 'User created successfully.');
    }

    /**
     * Deletes or activates an account.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAccount($id)
    {
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'User not found.');
        }

        $this->authorize('delete', $user);

        DB::beginTransaction();

        try {
            $user->active = !$user->active; // Toggle active status
            $user->save();

            DB::commit();

            return back()->with('success', 'Account successfully ' . ($user->active ? 'activated' : 'deactivated') . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Unable to change the account status.');
        }
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate the logo as an image
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/logos'), $logoName);

            // Store the logo path for the specific clinic
            $clinic = auth()->user()->clinic;  // Assuming the logged-in user has an associated clinic
            $clinic->logo = 'uploads/logos/' . $logoName;
            $clinic->save();

            return back()->with('success', 'Clinic logo updated successfully.');
        }

        return back()->with('error', 'Please upload a valid image file.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if an image is uploaded
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $name = time() . '.' . $image->getClientOriginalExtension();

            // Define the path to store the image
            $destinationPath = public_path('/uploads/avatars');

            // Move the uploaded file to the destination path
            $image->move($destinationPath, $name);

            // Update user's avatar in the database
            $user = Auth::user();
            $user->avatar = '/uploads/avatars/' . $name;
            $user->save();

            return back()->with('success', 'Avatar updated successfully!');
        }

        return back()->with('error', 'Please select a valid image file.');
    }

    public function updatePrintPreviewOption(Request $request)
    {
        $request->validate([
            'print_preview_option' => 'required|in:option1,option2,option3', // Customize with your actual options
        ]);

        // Get the setting row
        $setting = Setting::first();

        if ($setting) {
            $setting->print_preview_option = $request->print_preview_option;
            $setting->save();
            return back()->with('success', 'Print preview option updated successfully.');
        }

        return back()->with('error', 'Unable to update the print preview option.');
    }

}
