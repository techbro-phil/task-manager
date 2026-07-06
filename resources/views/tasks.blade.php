<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - Dashboard</title>
    <!-- Tailwind CSS loaded directly via CDN -->
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
                Welcome back! Your tasks are flowing dynamically from your local SQLite database.
            </p>
        </header>

        <!-- New Task Form Section -->
        <section class="mb-8 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Add a New Task</h2>
            <form action="/tasks" method="POST" class="flex gap-3">
                <!-- Laravel's CSRF Security Token Protection -->
                @csrf
                <input 
                    type="text" 
                    name="title" 
                    placeholder="What needs to be done?" 
                    class="flex-1 min-w-0 rounded-md border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none"
                    required
                >
                <button 
                    type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Add Task
                </button>
            </form>
        </section>

        <!-- Tasks List Section -->
        <main>
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul role="list" class="divide-y divide-gray-200">
                    <!-- Blade Loop: Navigating through database records -->
                    @foreach ($tasks as $task)
                        <li class="px-6 py-4 flex items-center hover:bg-gray-50 transition justify-between">
                            <div class="flex items-center">
                                <!-- Dynamic Status Indicator Circle Color based on task completion -->
                                <span class="w-2.5 h-2.5 rounded-full mr-4 {{ $task->is_completed ? 'bg-green-500' : 'bg-amber-500' }}"></span>
                                
                                <!-- Dynamic Task Title outputting the column object property -->
                                <span class="text-sm font-medium {{ $task->is_completed ? 'text-gray-400 line-through' : 'text-gray-700' }}">
                                    {{ $task->title }}
                                </span>
                            </div>

                            <!-- Visual Completion Status Tag badge -->
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $task->is_completed ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $task->is_completed ? 'Done' : 'Pending' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </main>
    </div>

</body>
</html>
