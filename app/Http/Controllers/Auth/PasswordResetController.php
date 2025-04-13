<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function showResetCodeForm()
    {
        if (!session('email')) {
            return redirect()->route('password.request')
                ->with('error', 'Please request a reset code first.');
        }
        return view('auth.reset-code');
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                return back()
                    ->withInput()
                    ->with('error', 'We could not find a user with that email address.');
            }

            // Delete any existing reset codes for this email
            DB::table('password_resets')->where('email', $request->email)->delete();

            // Generate a new reset code
            $resetCode = Str::random(6);
            
            // Store the reset token
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => Hash::make($resetCode),
                'created_at' => now()
            ]);

            // Send the reset code email
            Mail::to($request->email)->send(new ForgotPasswordMail($resetCode));

            // Store email in session for the next page
            session(['email' => $request->email]);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Password reset code has been sent to your email.',
                    'redirect' => route('password.code')
                ]);
            }

            return redirect()->route('password.code')
                ->with('success', 'Reset code has been sent to your email.');

        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'An error occurred while processing your request.'
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'An error occurred while processing your request.');
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'code' => 'required|string|size:6',
                'password' => 'required|string|min:8|confirmed'
            ]);

            $passwordReset = DB::table('password_resets')
                ->where('email', $request->email)
                ->where('created_at', '>', now()->subMinutes(15))
                ->first();

            if (!$passwordReset) {
                return back()
                    ->withInput()
                    ->with('error', 'Invalid or expired reset code.');
            }

            if (!Hash::check($request->code, $passwordReset->token)) {
                return back()
                    ->withInput()
                    ->with('error', 'Invalid reset code.');
            }

            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                return back()
                    ->withInput()
                    ->with('error', 'We could not find a user with that email address.');
            }

            $user->password = Hash::make($request->password);
            $user->save();

            // Delete all reset tokens for this email
            DB::table('password_resets')->where('email', $request->email)->delete();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Your password has been reset successfully.'
                ]);
            }

            return redirect()
                ->route('login')
                ->with('success', 'Your password has been reset successfully. Please login with your new password.');

        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'An error occurred while processing your request.'
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'An error occurred while processing your request.');
        }
    }
} 