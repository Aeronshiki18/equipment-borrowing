<x-mail::message>
# Password Reset Request

You requested to reset your password. Use the 6-digit OTP below to proceed. This code expires in 10 minutes.

<x-mail::panel>
# {{ $otp }}
</x-mail::panel>

If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>