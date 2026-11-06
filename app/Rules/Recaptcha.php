<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $value,
        ]);

        // TEMPORARY DEBUGGING LINE:
        // If it fails, this will stop the page and show you exactly what Google says.
        if (!($response->json('success') ?? false)) {
            dd($response->json()); 
            $fail('Please complete the reCAPTCHA verification.');
        }
    }
}