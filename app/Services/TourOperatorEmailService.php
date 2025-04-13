<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TourOperatorEmailService
{
    public function sendRegistrationEmail(User $user)
    {
        $data = [
            'name' => $user->full_name,
            'email' => $user->email,
            'login_url' => route('login'),
        ];

        Mail::send('emails.tour_operator_registration', $data, function($message) use ($user) {
            $message->to($user->email, $user->full_name)
                    ->subject('Welcome to Tourism Management System - Registration Successful');
        });
    }
} 