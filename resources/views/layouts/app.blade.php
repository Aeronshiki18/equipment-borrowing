<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Equipment Borrowing</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <a href="/" class="font-bold text-xl text-blue-600">Equipment System</a>
        <div class="space-x-4 text-sm font-medium flex items-center">
            <a href="/equipment" class="hover:text-blue-600">Equipment List</a>
            @auth
                <a href="/dashboard" class="hover:text-blue-600">Dashboard</a>
                <a href="/requests" class="hover:text-blue-600">My Requests</a>
                
                @if(auth()->user()->isStaff())
                    <a href="/staff/requests" class="text-blue-600 font-bold hover:text-blue-800">Staff Queue</a>
                @endif

                @if(auth()->user()->isAdmin())
                    <a href="/admin/equipment" class="text-purple-600 font-bold hover:text-purple-800">Admin Equip</a>
                    <a href="/admin/requests" class="text-purple-600 font-bold hover:text-purple-800">Admin Reqs</a>
                @endif
                
                <!-- CHANGED: Replaced the form with a button that opens the modal -->
                <button type="button" onclick="document.getElementById('logoutModal').classList.remove('hidden')" class="text-red-500 hover:underline ml-4">
                    Logout
                </button>
            @else
                <a href="/login" class="hover:text-blue-600">Login</a>
                <a href="/register" class="bg-blue-600 text-white px-3 py-1 rounded">Register</a>
            @endauth
        </div>
    </nav>
    
    <main class="max-w-6xl mx-auto mt-8 p-4">
        @if(session('success')) 
            <div class="bg-green-100 border border-green-400 text-green-700 p-3 mb-4 rounded">{{ session('success') }}</div> 
        @endif
        @yield('content')
    </main>

    <!-- ========================================== -->
    <!-- ADDED: CENTERED LOGOUT CONFIRMATION MODAL  -->
    <!-- ========================================== -->
    <div id="logoutModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <!-- Modal Box -->
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm text-center mx-4">
            <!-- Icon -->
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100 mb-4">
                <svg class="h-7 w-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                </svg>
            </div>
            
            <!-- Text -->
            <h3 class="text-lg font-bold text-gray-900 mb-2">Log out?</h3>
            <p class="text-sm text-gray-500 mb-6">Are you sure you want to log out of your account? You will need to sign in again to continue.</p>
            
            <!-- Buttons -->
            <div class="flex flex-col-reverse sm:flex-row sm:justify-center gap-3">
                <!-- Cancel Button -->
                <button type="button" onclick="document.getElementById('logoutModal').classList.add('hidden')" class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-50 font-semibold text-sm transition">
                    Cancel
                </button>
                
                <!-- Actual Logout Form -->
                <form method="POST" action="/logout" class="w-full sm:w-auto m-0">
                    @csrf 
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-500 font-semibold text-sm transition">
                        Yes, log out
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>