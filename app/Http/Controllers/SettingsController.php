<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SettingsController extends Controller
{
    /**
     * Shows the settings view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewSettings()
    {
        return view('settings.settings');
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
            'password'         => 'required|confirmed|min:6'
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
            'user_name'     => 'required|max:255',
            'user_username' => 'required|unique:users,username',
            'user_role'     => 'required|exists:roles,id|not_in:1', // Assuming ID 1 is Admin
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
}
