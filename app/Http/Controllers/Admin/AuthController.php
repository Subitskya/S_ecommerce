<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct()
    {
        // Middleware to restrict access to authenticated users
        $this->middleware('auth', ['except' => ['login', 'register', 'performLogin', 'performRegister','verifyEmail','performVerifyEmail','verifyMailToken','forgotPassword','performForgotPassword','resetPassword','performResetPassword']]);
    }

    public function login()
    {
        return view('auth.login');
    }

    public function performLogin(Request $request)
    {
        $emailVerify = User::where('email', $request->email)->first();
        if (empty($emailVerify->email_verified_at)) {
            return redirect()->route('verifyEmail')->with('error', 'Verify your Email Address');
        }

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
            'g-recaptcha-response' => ['required',function (string $attribute, mixed $value, Closure $fail) {
                $g_response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('services.recaptcha.secret'),
                    'response' => $value,
                    'remoteip' => \request()->ip()
                ]);

                if (!$g_response->json('success')) {
                    $fail("The {$attribute} is invalid");
                }
            }]
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // // Log in the user
        // Auth::login($user);
        if ($user) {
            return redirect()->route('verifyEmail')->with('success', 'Registration Successful!!!');
        } else {
            return redirect()->back()->with('error', 'Registration Failed!!!');
        }
    }

    public function verifyEmail()
    {
        return view('auth.verify-email');
    }

    public function performVerifyEmail(Request $request)
    {
        //Validation Rule
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        //Check if validation fails
        if ($validatedData->fails()) {
            return redirect()->back()->withErrors($validatedData->errors())->withInput();
        }

        //Random Token
        $random = Str::random(40);
        $domain = URL::to('/');
        $url = $domain.'/verify-email/'.$random;

        //Adding Token
        $user = User::where('email', $request->email)->first();
        $user->remember_token = $random;
        $user->save();

        //Sending Mail
        Mail::send('mails.verify-email', ['email' => $request->email,'url' => $url], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject("Verify Email Address");
        });

        //Response
        return redirect()->back()->with('success', 'Please check your email for Verification Link!!!');
    }

    public function verifyMailToken($token)
    {
        $user = User::where('remember_token', $token)->first();

        if ($user) {
            $user->remember_token = null;
            $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
            $user->save();

            //Response
            return redirect()->route('login')->with('success', 'Email Verified Successfully!!!');
        } else {
            //Response
            return redirect()->route('verifyEmail')->with('error', 'Invalid Link');
        }
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function performForgotPassword(Request $request)
    {
        //Validation Rule
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        //Check if validation fails
        if ($validatedData->fails()) {
            return redirect()->back()->withErrors($validatedData->errors())->withInput();
        }

        $token = Str::random(64);

        //Check Records in Password Reset Table
        $checkRecords = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if ($checkRecords) {
            DB::table('password_reset_tokens')->where('email', $request->email)->update([
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        } else {
            DB::table('password_reset_tokens')->insert([
                'token' => $token,
                'email' => $request->email,
                'created_at' => Carbon::now()
            ]);
        }

        //Sending Mail
        Mail::send('mails.forget-password', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject("Reset Password");
        });

        //Response
        return redirect()->back()->with('success', 'Please check your email for Reset Password Link!!!');
    }

    public function resetPassword($token)
    {
        $check = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$check) {
            return redirect()->route('forgotPassword')->with('error', 'Invalid Link');
        }

        return view('auth.reset-password', compact('token'));
    }

    public function performResetPassword(Request $request)
    {
        //Validation Rule
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        //Check if validation fails
        if ($validatedData->fails()) {
            return redirect()->back()->withErrors($validatedData->errors())->withInput();
        }

        //Check Records in Password Reset Table
        $updatePassword = DB::table('password_reset_tokens')->where([
            'email' => $request->email,
            'token' => $request->token
        ])->first();

        if (!$updatePassword) {
            return redirect()->back()->with('error', 'Invalid Email Address');
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        //Response
        return redirect()->route('login')->with('success', 'Password Reset Successfully!!!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }
}
