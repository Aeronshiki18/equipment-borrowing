@props(['id' => 'logout-confirmation'])

<div
    x-data="{ show: false }"
    x-on:open-logout-modal.window="show = true"
    x-on:close-logout-modal.window="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    x-cloak
    id="{{ $id }}"
    class="fixed inset-0 z-[9999]"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
>
    {{-- Backdrop --}}
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
        @click="show = false"
        aria-hidden="true"
    ></div>

    {{-- Centered panel wrapper --}}
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="w-full max-w-md overflow-hidden rounded-2xl bg-white text-center shadow-2xl ring-1 ring-black/5"
        >
            <div class="px-6 pt-6 pb-4">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100">
                    <svg class="h-7 w-7 text-red-600" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                    </svg>
                </div>

                <h3 id="{{ $id }}-title" class="mt-4 text-lg font-semibold text-gray-900">
                    {{ __('Log out?') }}
                </h3>

                <p class="mt-2 text-sm text-gray-600">
                    {{ __('Are you sure you want to log out of your account? You will need to sign in again to continue.') }}
                </p>
            </div>

            <div class="flex flex-col-reverse gap-3 bg-gray-50 px-6 py-4 sm:flex-row sm:justify-center">
                <button
                    type="button"
                    @click="show = false"
                    class="inline-flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                >
                    {{ __('Cancel') }}
                </button>

                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                    >
                        {{ __('Yes, log out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>