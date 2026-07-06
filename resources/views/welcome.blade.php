<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - Dashboard</title>
    <!-- We load Tailwind CSS directly via CDN for fast styling setup -->
    <script src="https://tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900">

    <div class="max-w-2xl mx-auto py-12 px-4">
        <!-- Header Section -->
        <header class="mb-8 border-b border-gray-200 pb-5">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">
                📝 Task Management Tool
            </h1>
            <p class="mt-2 text-sm text-gray-600">
                Welcome back! Here is an overview of your current milestones.
            </p>
        </header>

        <!-- Tasks List Section -->
        <main>
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul role="list" class="divide-y divide-gray-200">
                    <!-- Blade Loop: This automatically iterates through the array we passed from the route -->
                    @foreach ($tasks as $task)
                        <li class="px-6 py-4 flex items-center hover:bg-gray-50 transition">
                            <span class="w-2.5 h-2.5 bg-blue-500 rounded-full mr-4"></span>
                            <span class="text-sm font-medium text-gray-700">
                                {{ $task }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </main>
    </div>

</body>
</html>
