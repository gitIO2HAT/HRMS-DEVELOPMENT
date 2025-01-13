<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LoginDashboardController extends Controller
{



    public function login(Request $request)
    {

        return view('loginform.login');
        
    }

    public function forgetpassword(Request $request)
    {
        return view('loginform.forget');
    }

    public function AuthLogin(Request $request)
    {
        // Retrieve user by email first
        $user = User::where('email', $request->email)->first();

        // Check if user exists and if the end_of_contract date has passed
        if ($user && Carbon::parse($user->end_of_contract)->isPast()) {
            return redirect()->back()->with('error', 'Your contract has ended. Please contact the administrator.');
        }

        // Attempt to log in with the provided credentials
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, true)) {
            $viewPath = Auth::user()->user_type == 0
                ? 'SuperAdmin/Dashboard'
                : (Auth::user()->user_type == 1
                    ? 'Admin/Dashboard'
                    : 'Employee/Dashboard');

            return redirect($viewPath);
        } else {
            return redirect()->back()->with('error', 'Please input the correct email and password.');
        }
    }

    public function sendResetLinkEmail(Request $request)
{
    try {
        // Validate email input
        $request->validate(['email' => 'required|email']);

        // Check if the user exists and is not archived
        $user = User::where('email', $request->input('email'))
        ->where('is_archive','=',1)->first();

        if (!$user) {
            // Log the missing email issue
            Log::warning('Password reset attempted for non-existent email: ' . $request->input('email'));

            // Return error message to user
            return back()->withErrors(['email' => 'The provided email address does not exist in our records.']);
        }

        if ($user->is_archived == 1) {
            // Log the archived account issue
            Log::warning('Password reset attempted for archived account: ' . $request->input('email'));

            // Return error message to user
            return back()->withErrors(['email' => 'This account is archived and cannot be reset. Please contact support.']);
        }

        // Send password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Log success status
        Log::info('Password reset link status: ' . $status);

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);

    } catch (\Exception $e) {
        // Log the exception
        Log::error('Error in sending password reset link: ' . $e->getMessage());

        // Return with a general error message
        return back()->withErrors(['email' => 'There was an error sending the password reset link. Try Again Later']);
    }
}

    public function logoutButton()
    {
        Auth::logout();
        return redirect(url(''));
    }
}
