<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Task Manager</title>
    <script src="https://tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900 flex items-center justify-center min-h-screen">

    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md text-center border border-gray-200">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2">📋 Task Manager</h1>
        <p class="text-sm text-gray-600 mb-8">Securely manage, track, and optimize your personal milestones.</p>

        <div class="space-y-4">
            @if (Route::has('login'))
                @auth
                    <!-- If a user is somehow logged in, show a dashboard button -->
                    <a href="{{ url('/tasks') }}" class="w-full inline-flex justify-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition">
                        Go to My Tasks
                    </a>
                @else
                    <!-- Login Button -->
                    <a href="{{ route('login') }}" class="w-full inline-flex justify-center px-4 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition">
                        Log In
                    </a>

                    @if (Route::has('register'))
                        <!-- Register Button -->
                        <a href="{{ route('register') }}" class="w-full inline-flex justify-center px-4 py-2 text-sm font-medium rounded-md text-blue-600 bg-blue-50 border border-blue-200 hover:bg-blue-100 transition">
                            Register a New Account
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>

</body>
</html>
