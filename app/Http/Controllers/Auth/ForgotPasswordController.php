<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    //New Forget Password
    public function forgetPassword(Request $request)
    {
        //Validation Rule
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);

        //Check if validation fails
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(), 400);
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
        return response()->json([
            'success' => true,
            'message' => 'Please check your email for a link to reset your password.',
            'token' => $token
        ]);
    }

    public function resetPasswordSubmit(Request $request)
    {
        //Validation Rule
        $validatedData = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        //Check if validation fails
        if ($validatedData->fails()) {
            return response()->json($validatedData->errors(), 400);
        }

        //Check Records in Password Reset Table
        $updatePassword = DB::table('password_reset_tokens')->where([
            'email' => $request->email,
            'token' => $request->token
        ])->first();

        if (!$updatePassword) {
            return response()->json([
                'success' => false,
                'message' => 'This link is not valid',
            ]);
        }
        
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'You have successfully reset your password!!!'
        ]);
    }
    //New Forget Password
}
