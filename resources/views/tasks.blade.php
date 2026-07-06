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
                Welcome back! Manage, update, and track your active milestones seamlessly.
            </p>
        </header>

        <!-- New Task Form Section -->
        <section class="mb-8 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Add a New Task</h2>
            <form action="/tasks" method="POST" class="flex gap-3">
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
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none"
                >
                    Add Task
                </button>
            </form>
        </section>

        <!-- Tasks List Section -->
        <main>
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul role="list" class="divide-y divide-gray-200">
                    <!-- Check if any tasks exist in the database -->
                    @if($tasks->isEmpty())
                        <li class="px-6 py-12 text-center text-sm text-gray-500">
                            🎉 No tasks left! Enjoy your clear workspace.
                        </li>
                    @endif

                    @foreach ($tasks as $task)
                        <li class="px-6 py-4 flex items-center hover:bg-gray-50 transition justify-between">
                            <div class="flex items-center flex-1">
                                <!-- Status circle -->
                                <span class="w-2.5 h-2.5 rounded-full mr-4 {{ $task->is_completed ? 'bg-green-500' : 'bg-amber-500' }}"></span>
                                
                                <span class="text-sm font-medium {{ $task->is_completed ? 'text-gray-400 line-through' : 'text-gray-700' }}">
                                    {{ $task->title }}
                                </span>
                            </div>

                            <div class="flex items-center gap-3">
                                <!-- UPDATE STATUS BUTTON FORM -->
                                <form action="/tasks/{{ $task->id }}" method="POST">
                                    @csrf
                                    @method('PUT') <!-- Forces the browser to send a PUT request -->
                                    <button type="submit" class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border transition cursor-pointer {{ $task->is_completed ? 'bg-green-100 text-green-800 border-green-200 hover:bg-green-200' : 'bg-amber-100 text-amber-800 border-amber-200 hover:bg-amber-200' }}">
                                        {{ $task->is_completed ? 'Done' : 'Pending' }}
                                    </button>
                                </form>

                                <!-- DELETE TASK BUTTON FORM -->
                                <form action="/tasks/{{ $task->id }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                    @csrf
                                    @method('DELETE') <!-- Forces the browser to send a DELETE request -->
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold px-2 py-1 transition cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </main>
    </div>

</body>
</html>
