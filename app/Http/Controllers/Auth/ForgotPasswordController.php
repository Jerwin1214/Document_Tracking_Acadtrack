<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ForgotPasswordController extends Controller
{
    /**
     * ✅ Show the "Forgot Password" form
     */
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * ✅ Handle sending the verification code to email via Brevo API
     */
    public function sendCode(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'email' => 'required|email',
        ]);

        // ✅ Check if both User ID and Email match in the users table
        $user = User::where('user_id', $request->user_id)
                    ->where('email', $request->email)
                    ->first();

        if (!$user) {
            return back()->with('error', 'User ID and Email do not match our records.');
        }

        // ✅ Generate a 6-digit code
        $code = rand(100000, 999999);

        // ✅ Save or update in password_resets table
        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $code, 'created_at' => now()]
        );

        // ✅ Send verification email via Brevo API
        try {
            $response = Http::withHeaders([
                'api-key' => env('BREVO_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => 'Acadtrack System',
                    'email' => env('MAIL_FROM_ADDRESS'),
                ],
                'to' => [
                    ['email' => $user->email]
                ],
                'subject' => 'Acadtrack Password Reset Code',
                'textContent' => "Your Acadtrack password reset code is: {$code}"
            ]);

            if ($response->failed()) {
                return back()->with('error', 'Failed to send email. Please try again later.');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Error sending email: ' . $e->getMessage());
        }

        // ✅ Redirect to verification form
        return redirect()->route('forgotPassword.verifyForm')->with([
            'success' => 'Verification code sent to your email.',
            'email' => $user->email
        ]);
    }

    /**
     * ✅ Show verification code form
     */
    public function showVerifyForm()
    {
        return view('auth.verify-code');
    }

    /**
     * ✅ Handle verification code submission
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|numeric',
        ]);

        // Check if the code matches
        $record = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->code)
            ->first();

        if (!$record) {
            return back()->with('error', 'Invalid or expired verification code.');
        }

        // Redirect to reset password form with email
        return redirect()->route('forgotPassword.resetForm', ['email' => $request->email]);
    }

    /**
     * ✅ Show the reset password form
     */
    public function showResetForm(Request $request)
    {
        $email = $request->query('email');
        return view('auth.reset-password', compact('email'));
    }

    /**
     * ✅ Handle password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        // Find user
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Remove reset record
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password successfully reset! You can now log in.');
    }
}
