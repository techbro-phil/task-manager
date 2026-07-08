<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zilla+Slab:wght@500;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        paper: '#FBF8F2',
                        rule: '#E7DFCE',
                        ink: '#26241E',
                        inksoft: '#6B6558',
                        moss: '#4B6E58',
                        mosssoft: '#E4EBE6',
                        ochre: '#B8813A',
                        ochresoft: '#F5EADA',
                        rust: '#A24936',
                    },
                    fontFamily: {
                        display: ['"Zilla Slab"', 'serif'],
                        body: ['"Inter"', 'sans-serif'],
                        mono: ['"IBM Plex Mono"', 'monospace'],
                    },
                },
            },
        };
    </script>

    <style>
        .check-square { transition: background-color .15s ease, border-color .15s ease, transform .1s ease; }
        .check-square:active { transform: scale(0.92); }
        .tab-hole {
            width: 6px; height: 6px; border-radius: 9999px;
            background: #FBF8F2; border: 1px solid #E7DFCE;
        }
        .task-card { transition: box-shadow .15s ease, border-color .15s ease; }
        .task-card:hover { border-color: #D8CEB4; }
        ::selection { background: #F5EADA; }
    </style>
</head>
<body class="bg-paper font-body text-ink antialiased">

    <div class="max-w-5xl mx-auto py-12 px-4">

        <!-- Header -->
        <header class="mb-10 pb-5 border-b-2 border-ink flex justify-between items-end">
            <div>
                <h1 class="font-display font-bold text-3xl tracking-tight text-ink">
                    Task Manager
                </h1>
                <p class="mt-1 font-mono text-xs uppercase tracking-widest text-inksoft">
                    {{ $tasks->where('is_completed', false)->count() }} open · {{ $tasks->count() }} total
                </p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="font-mono text-xs uppercase tracking-widest text-inksoft hover:text-ink transition">
                    Log Out
                </button>
            </form>
        </header>

        <!-- Flash Success Message -->
        @if (session('success'))
            <div class="mb-6 rounded border border-moss/30 bg-mosssoft px-4 py-3 text-sm text-moss font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Validation Error Messages -->
        @if ($errors->any())
            <div class="mb-6 rounded border border-rust/30 bg-rust/5 px-4 py-3 text-sm text-rust">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- LEFT COLUMN: Categories -->
            <div class="space-y-6">
                <section class="bg-white p-6 rounded-lg border border-rule shadow-sm">
                    <h2 class="font-display font-bold text-base text-ink mb-4">New Category</h2>
                    <form action="{{ route('categories.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <input
                            type="text"
                            name="name"
                            placeholder="e.g., Work, Personal, Bills"
                            class="w-full rounded border border-rule px-3 py-2 text-sm bg-paper focus:border-ochre focus:ring-1 focus:ring-ochre outline-none"
                            required
                        >
                        <button type="submit" class="w-full inline-flex justify-center px-4 py-2 rounded text-sm font-medium text-white bg-ink hover:bg-ink/90 transition">
                            Add Category
                        </button>
                    </form>
                </section>

                <section class="bg-white p-6 rounded-lg border border-rule shadow-sm">
                    <h3 class="font-mono text-xs uppercase tracking-widest text-inksoft mb-4">Filter by Label</h3>

                    <div class="mb-4 pb-3 border-b border-rule">
                        <a href="{{ route('tasks.index') }}" class="font-mono text-xs uppercase tracking-wide {{ request('category_id') ? 'text-ochre hover:text-ink' : 'text-ink underline underline-offset-2' }}">
                            Show All
                        </a>
                    </div>

                    @if($categories->isEmpty())
                        <p class="text-xs text-inksoft italic">No labels created yet.</p>
                    @else
                        <div class="space-y-2">
                            @foreach($categories as $category)
                                <div class="flex items-center justify-between gap-2 px-2 py-1.5 rounded {{ request('category_id') == $category->id ? 'bg-ochresoft' : '' }}">
                                    <a href="{{ route('tasks.index', ['category_id' => $category->id]) }}" class="flex items-center gap-2 text-sm font-medium {{ request('category_id') == $category->id ? 'text-ochre' : 'text-inksoft hover:text-ink' }}">
                                        <span class="tab-hole"></span>
                                        {{ $category->name }}
                                    </a>

                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <details class="relative">
                                            <summary class="cursor-pointer font-mono text-[10px] uppercase text-inksoft/60 hover:text-inksoft list-none select-none">Edit</summary>
                                            <form action="{{ route('categories.update', $category) }}" method="POST" class="mt-2 flex gap-1">
                                                @csrf
                                                @method('PUT')
                                                <input
                                                    type="text"
                                                    name="name"
                                                    value="{{ $category->name }}"
                                                    class="text-xs rounded border border-rule px-2 py-1 w-24 bg-paper focus:border-ochre focus:ring-1 focus:ring-ochre outline-none"
                                                    required
                                                >
                                                <button type="submit" class="font-mono text-[10px] uppercase text-moss hover:text-ink">Save</button>
                                            </form>
                                        </details>

                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category? Tasks will keep their titles but lose this label.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-mono text-[10px] uppercase text-rust/70 hover:text-rust">Del</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>

            <!-- RIGHT COLUMN: Tasks -->
            <div class="md:col-span-2 space-y-6">
                <!-- Task Entry Form -->
                <section class="bg-white p-6 rounded-lg border border-rule shadow-sm">
                    <h2 class="font-display font-bold text-base text-ink mb-4">Add a Task</h2>
                    <form action="{{ route('tasks.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <input
                            type="text"
                            name="title"
                            placeholder="What needs to be done?"
                            class="flex-1 min-w-0 rounded border-0 border-b-2 border-rule bg-transparent px-1 py-2 text-sm font-mono focus:border-ochre outline-none"
                            required
                        >

                        <select name="category_id" class="rounded border border-rule px-3 py-2 text-sm text-inksoft bg-paper focus:border-ochre focus:ring-1 focus:ring-ochre outline-none">
                            <option value="">No Label</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="inline-flex justify-center items-center px-5 py-2 rounded text-sm font-medium text-white bg-ochre hover:bg-ochre/90 transition">
                            Add
                        </button>
                    </form>
                </section>

                <!-- Tasks List -->
                <main>
                    <div class="rounded-lg overflow-hidden">
                        <ul role="list" class="space-y-2">
                            @if($tasks->isEmpty())
                                <li class="bg-white border border-dashed border-rule rounded-lg px-6 py-16 text-center">
                                    <p class="text-sm text-inksoft">Nothing here yet.</p>
                                    <p class="text-xs text-inksoft/60 mt-1">Add your first task above to get started.</p>
                                </li>
                            @endif

                            @foreach ($tasks as $task)
                                <li class="task-card bg-white border border-rule rounded-lg px-5 py-4 flex items-center justify-between shadow-sm">
                                    <div class="flex items-center flex-1 min-w-0 gap-4">
                                        <form action="{{ route('tasks.update', $task) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" aria-label="{{ $task->is_completed ? 'Mark as pending' : 'Mark as done' }}"
                                                class="check-square w-6 h-6 rounded-sm border-2 flex items-center justify-center flex-shrink-0 {{ $task->is_completed ? 'bg-moss border-moss' : 'border-inksoft/30 hover:border-ochre' }}">
                                                @if($task->is_completed)
                                                    <svg viewBox="0 0 24 24" class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M4 12l5 5L20 6" />
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>

                                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 min-w-0">
                                            <span class="text-sm font-medium truncate {{ $task->is_completed ? 'text-inksoft/50 line-through' : 'text-ink' }}">
                                                {{ $task->title }}
                                            </span>
                                            @if($task->category)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded font-mono text-[10px] uppercase tracking-wide bg-ochresoft text-ochre w-fit">
                                                    {{ $task->category->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');" class="ml-4 flex-shrink-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-mono text-[10px] uppercase tracking-wide text-rust/50 hover:text-rust transition">
                                            Delete
                                        </button>
                                    </form>
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