<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        // Middleware to restrict access to authenticated users
        $this->middleware('auth', ['except' => ['login', 'register', 'performLogin', 'performRegister']]);
    }

    public function login()
    {
        return view('auth.login');
    }

    public function performLogin(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return redirect()->back()->withErrors([
                'email' => 'Invalid credentials',
            ])->withInput($request->only('email'));
        }

        // Redirect to a specific route after successful login
        return redirect()->route('admin.dashboard')->with('success', 'Login successful');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function performRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed', // Added confirmation for better UX
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Log in the user
        Auth::login($user);

        // Redirect to a specific route after successful registration
        return redirect()->route('admin.dashboard')->with('success', 'Registration successful');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }
}
