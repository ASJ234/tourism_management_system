<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestEmailController extends Controller
{
    public function testEmail()
    {
        try {
            $data = [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'login_url' => route('login'),
            ];

            Mail::send('emails.tour_operator_registration', $data, function($message) {
                $message->to('test@example.com', 'Test User')
                        ->subject('Test Email from Tourism Management System');
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Test email sent successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ], 500);
        }
    }
} 