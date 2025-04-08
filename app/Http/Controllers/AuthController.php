<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\EmployeeApplicationTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function user_login_view()
    {
        return view('Auth.user_login');
    }

    public function super_admin_login_view()
    {
        return view('Auth.super_admin_login');
    }

    public function hr_login_view()
    {
        return view('Auth.hr_login');
    }

    public function user_register($token)
    {
        $user = User::where('token_to_set_password', $token)->first();
        if ($user) {
            if ($user->password_set != 1) {
                return view('Auth.user_register', compact('user'));
            } else {
                return view('Auth.page_expired');
            }
        } else {
            return view('Auth.page_exipred');
        }
    }

    public function user_login_post(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt to authenticate the user
        if (!Auth::attempt($credentials)) {
            return redirect()->route('user_login_view')->with('error', 'Invalid email or password');
        }

        // Get the authenticated user
        $user = auth()->user();

        // Check if the user's role is 3 or 4 (3 is for regular employees and 4 is for Reportin managers)
        
        if($user->employee_status == 0 ){
             Auth::logout();
            return redirect()->route('user_login_view')->with('error', 'You cannot Access the portal');
    
        }
        else if ($user->role_id == 3 || $user->role_id == 4) {
            // Check if the employee's application time record exists for the current date
            $application_time = EmployeeApplicationTime::where('user_id', $user->id)
                ->where('date', date('Y-m-d'))
                ->first();

            if (!$application_time) {
                // If the application time record doesn't exist, create a new record with initial time value of "0"
                $application_time = new EmployeeApplicationTime();
                $application_time->user_id = $user->id;
                $application_time->date = date('Y-m-d');
                $application_time->time = "0";
                $application_time->save();
            }

            return redirect()->route('user_dashboard');
        } 
        
       
        else {
            // If the user's role is not 3 or 4, logout and redirect with an error message
            Auth::logout();
            return redirect()->route('user_login_view')->with('error', 'UnAuthorized Access');
        }
    }
    
    public function super_admin_login_post(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return  redirect()->route('super_admin_login_view')->with('error', 'Invalid email or password');
        }
        $user = User::where('email', $request->email)->first();
        if ($user->role_id == 1) {
            return redirect()->route('super_admin_dashboard');
        } else {
            Auth::logout();
            return  redirect()->route('super_admin_login_view')->with('error', 'UnAuthorized Access');
        }
    }

    public function hr_login_post(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return  redirect()->route('hr_login_view')->with('error', 'Invalid email or password');
        }
        $user = User::where('email', $request->email)->first();
        
         if($user->employee_status == 0 ){
             Auth::logout();
            return redirect()->route('hr_login_view')->with('error', 'You cannot Access the portal  ');
    
        }
        else if ($user->role_id == 2) {
            $application_time = EmployeeApplicationTime::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->first();
            if (!$application_time) {
                $application_time = new EmployeeApplicationTime();
                $application_time->user_id = auth()->user()->id;
                $application_time->date = date('Y-m-d');
                $application_time->time = "0";
                $application_time->save();
            }

            return redirect()->route('hr_dashboard');
        } else {
            Auth::logout();
            return  redirect()->route('hr_login_view')->with('error', 'UnAuthorized Access');
        }
    }

    public function user_register_post(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($request->password != $request->confirm_password) {
            return redirect()->route('user_register',['token'=>$user->token_to_set_password])->with('error', 'Password Does Not Match With Confirm Password');
        }
        
        if ($user->password_set !=1) {
            $user->password_set = 1;
            $user->password = Hash::make($request->password);
            $user->save();
            Auth::login($user);
            if ($user->role_id == 3) {
                return redirect()->route('user_dashboard');
            }
            if ($user->role_id == 2) {
                return redirect()->route('hr_dashboard');
            } else {
                return redirect()->route('user_register', ['token' => $user->token_to_set_password])->with('error', 'Something Went Wrong');
            }
        } else {
            return redirect()->route('user_register', ['token' => $user->token_to_set_password])->with('error', 'Password Already Created');
        }
    }
    public function logout()
    {
        // Get the authenticated user
        $user = auth()->user();

        Auth::logout();
        Session::flush();

        if ($user->role_id == 1) {
            return redirect()->route('super_admin_login_view');
        } elseif ($user->role_id == 2) {
            return redirect()->route('hr_login_view');
        } else {
            return redirect()->route('user_login_view');
        }
    }

    public function update_application_time()
    {
        $application_time = EmployeeApplicationTime::where('user_id', auth()->user()->id)->where('date', date('Y-m-d'))->first();
        if (!$application_time) {
            $application_time = new EmployeeApplicationTime();
            $application_time->user_id = auth()->user()->id;
            $application_time->date = date('Y-m-d');
            $application_time->time = 1;
            $application_time->save();
        } else {
            $application_time->time = $application_time->time + 1;
            $application_time->save();
        }
        return 0;
    }
}
