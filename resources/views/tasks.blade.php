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

    <div class="max-w-5xl mx-auto py-12 px-4">
        <!-- Header Section -->
        <header class="mb-8 border-b border-gray-200 pb-5 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">
                    📝 Task Management Tool
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    Click on your category badges in the sidebar to instantly filter your workspace.
                </p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-semibold text-gray-500 hover:text-gray-700 bg-transparent border-0 cursor-pointer">
                    Log Out
                </button>
            </form>
        </header>

        <!-- Flash Success Message -->
        @if (session('success'))
            <div class="mb-6 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <!-- Validation Error Messages -->
        @if ($errors->any())
            <div class="mb-6 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Two Column Dashboard Layout Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- LEFT COLUMN: Categories Sidebar Manager -->
            <div class="space-y-6">
                <section class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Create a Category</h2>
                    <form action="{{ route('categories.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <input
                            type="text"
                            name="name"
                            placeholder="e.g., Work, Personal, Bills"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none"
                            required
                        >
                        <button type="submit" class="w-full inline-flex justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-900 focus:outline-none">
                            Add Category
                        </button>
                    </form>
                </section>

                <!-- Clickable Interactive Labels Card -->
                <section class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Filter by Label</h3>

                    <!-- Clear filter option reset link -->
                    <div class="mb-4 pb-3 border-b border-gray-100">
                        <a href="{{ route('tasks.index') }}" class="text-xs font-semibold {{ request('category_id') ? 'text-blue-600 hover:text-blue-800' : 'text-gray-900 underline' }}">
                            📁 Show All Active Tasks
                        </a>
                    </div>

                    @if($categories->isEmpty())
                        <p class="text-xs text-gray-400 italic">No labels created yet.</p>
                    @else
                        <div class="space-y-2">
                            @foreach($categories as $category)
                                <div class="flex items-center justify-between gap-2">
                                    
                                        href="{{ route('tasks.index', ['category_id' => $category->id]) }}"
                                        class="px-2.5 py-1 text-xs font-medium rounded-md border transition {{ request('category_id') == $category->id ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-blue-50 text-blue-700 border-blue-100 hover:bg-blue-100' }}"
                                    >
                                        {{ $category->name }}
                                    </a>

                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <details class="relative">
                                            <summary class="cursor-pointer text-xs text-gray-400 hover:text-gray-600 list-none select-none">Rename</summary>
                                            <form action="{{ route('categories.update', $category) }}" method="POST" class="mt-2 flex gap-1">
                                                @csrf
                                                @method('PUT')
                                                <input
                                                    type="text"
                                                    name="name"
                                                    value="{{ $category->name }}"
                                                    class="text-xs rounded border border-gray-300 px-2 py-1 w-24 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none"
                                                    required
                                                >
                                                <button type="submit" class="text-xs font-medium text-blue-600 hover:text-blue-800">Save</button>
                                            </form>
                                        </details>

                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category? Tasks will keep their titles but lose this label.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-400 hover:text-red-600">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>

            <!-- RIGHT COLUMN: Tasks CRUD Manager -->
            <div class="md:col-span-2 space-y-6">
                <!-- Task Entry Form Card -->
                <section class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Add a New Task</h2>
                    <form action="{{ route('tasks.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <input
                            type="text"
                            name="title"
                            placeholder="What needs to be done?"
                            class="flex-1 min-w-0 rounded-md border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none"
                            required
                        >

                        <select name="category_id" class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-600 bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                            <option value="">No Label</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="inline-flex justify-center items-center px-5 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                            Add Task
                        </button>
                    </form>
                </section>

                <!-- Tasks Listing view box -->
                <main>
                    <div class="bg-white shadow overflow-hidden sm:rounded-md border border-gray-200">
                        <ul role="list" class="divide-y divide-gray-200">
                            @if($tasks->isEmpty())
                                <li class="px-6 py-12 text-center text-sm text-gray-500">
                                    🔍 No matching tasks found for this view criteria.
                                </li>
                            @endif

                            @foreach ($tasks as $task)
                                <li class="px-6 py-4 flex items-center hover:bg-gray-50 transition justify-between">
                                    <div class="flex items-center flex-1 min-w-0">
                                        <span class="w-2.5 h-2.5 rounded-full mr-4 flex-shrink-0 {{ $task->is_completed ? 'bg-green-500' : 'bg-amber-500' }}"></span>

                                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                            <span class="text-sm font-medium truncate {{ $task->is_completed ? 'text-gray-400 line-through' : 'text-gray-700' }}">
                                                {{ $task->title }}
                                            </span>
                                            @if($task->category)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                                    {{ $task->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3 ml-4 flex-shrink-0">
                                        <form action="{{ route('tasks.update', $task) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border transition cursor-pointer {{ $task->is_completed ? 'bg-green-100 text-green-800 border-green-200 hover:bg-green-200' : 'bg-amber-100 text-amber-800 border-amber-200 hover:bg-amber-200' }}">
                                                {{ $task->is_completed ? 'Done' : 'Pending' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                            @csrf
                                            @method('DELETE')
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

        </div>
    </div>

</body>
</html>