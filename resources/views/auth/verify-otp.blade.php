<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('We sent a 6-digit OTP to your email. Enter it below to verify.') }}
    </div>
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.verify.otp') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <div>
            <x-input-label for="otp" :value="__('OTP Code')" />
            <x-text-input id="otp" class="block mt-1 w-full text-center tracking-widest text-2xl font-bold" type="text" name="otp" required autofocus maxlength="6" placeholder="000000" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">
            <x-primary-button>{{ __('Verify OTP') }}</x-primary-button>
        </div>
    </form>
</x-guest-layout>