@component('mail::message')
# Password Reset Code

You have requested to reset your password. Here is your reset code:

@component('mail::panel')
{{ $resetCode }}
@endcomponent

This code will expire in 15 minutes. If you did not request a password reset, please ignore this email.

@component('mail::button', ['url' => route('password.code')])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent 