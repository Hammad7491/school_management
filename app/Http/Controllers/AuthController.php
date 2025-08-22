<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function logout()
    {
        Auth::logout();
        return redirect()->route('loginform');
    }

    public function loginform()
    {
        return view('auth.login');
    }

    public function registerform()
    {
        return view('auth.register');
    }

    public function error403()
    {
        return view('auth.errors.error403');
    }

    /**
     * Handle registration (manual or social data).
     * (You probably don't use this for students, but kept intact.)
     */
    public function register(Request $request)
    {
        // Social quick-create
        if ($request->has('google_user_data') || $request->has('facebook_user_data')) {
            $socialKey = $request->has('google_user_data') ? 'google_user_data' : 'facebook_user_data';
            $data      = $request->input($socialKey);

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'      => $data['name'],
                    'avatar'    => $data['avatar'] ?? null,
                    $socialKey === 'google_user_data' ? 'google_id' : 'facebook_id'
                        => $data[$socialKey === 'google_user_data' ? 'google_id' : 'facebook_id'],
                    'password'  => Hash::make(Str::random(16)),
                ]
            );

            Auth::login($user, true);
            // Staff/student redirect rule
            if (Student::where('student_id', $user->id)->exists()) {
                return redirect()->route('students.dashboard');
            }
            return redirect()->route('admin.dashboard');
        }

        // Manual registration (non-student)
        $validator = Validator::make($request->all(), [
            'name'     => 'required|unique:users,name',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // If you use spatie, you can assign a staff role here.
        // $user->assignRole('Client'); // optional

        return redirect()->route('loginform')->with('success', 'Registration successful');
    }

    /**
     * Handle login and redirect:
     *  - Students (exist in students.student_id) -> students.dashboard
     *  - Everyone else (staff) -> admin.dashboard
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($credentials, $request->filled('remember'))) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        $user = Auth::user();

        // If this user is linked as a student, go to students dashboard
        if (Student::where('student_id', $user->id)->exists()) {
            return redirect()->route('student.dashboard');
        }

        // All other users (staff of any role) go to the admin dashboard.
        return redirect()->route('admin.dashboard');
    }
}
