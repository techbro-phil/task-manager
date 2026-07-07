<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Task Manager</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Zilla+Slab:wght@500;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@500&display=swap" rel="stylesheet">

    <script src="https://tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        paper: '#FBF8F2', rule: '#E7DFCE', ink: '#26241E',
                        inksoft: '#6B6558', moss: '#4B6E58', mosssoft: '#E4EBE6',
                        ochre: '#B8813A', ochresoft: '#F5EADA', rust: '#A24936',
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
</head>
<body class="bg-paper font-body text-ink antialiased">

    <div class="max-w-2xl mx-auto py-12 px-4">

        <!-- Header -->
        <header class="mb-10 pb-5 border-b-2 border-ink flex justify-between items-end">
            <div>
                <h1 class="font-display font-bold text-3xl tracking-tight text-ink">Profile</h1>
                <a href="{{ route('tasks.index') }}" class="mt-1 inline-block font-mono text-xs uppercase tracking-widest text-inksoft hover:text-ink transition">
                    ← Back to Tasks
                </a>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="font-mono text-xs uppercase tracking-widest text-inksoft hover:text-ink transition">
                    Log Out
                </button>
            </form>
        </header>

        <div class="space-y-6">

            <!-- Profile Information -->
            <section class="bg-white p-6 rounded-lg border border-rule shadow-sm">
                <h2 class="font-display font-bold text-base text-ink mb-1">Profile Information</h2>
                <p class="text-sm text-inksoft mb-6">Update your account's name and email address.</p>

                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block font-mono text-xs uppercase tracking-widest text-inksoft mb-1">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                            class="w-full rounded border border-rule px-3 py-2 text-sm bg-paper focus:border-ochre focus:ring-1 focus:ring-ochre outline-none">
                        @error('name')
                            <p class="mt-1 text-xs text-rust">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block font-mono text-xs uppercase tracking-widest text-inksoft mb-1">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                            class="w-full rounded border border-rule px-3 py-2 text-sm bg-paper focus:border-ochre focus:ring-1 focus:ring-ochre outline-none">
                        @error('email')
                            <p class="mt-1 text-xs text-rust">{{ $message }}</p>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-2">
                                <p class="text-xs text-inksoft">
                                    Your email address is unverified.
                                    <button form="send-verification" class="underline text-ochre hover:text-ink">
                                        Click here to re-send the verification email.
                                    </button>
                                </p>
                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-1 text-xs text-moss font-medium">
                                        A new verification link has been sent to your email address.
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="inline-flex justify-center px-4 py-2 rounded text-sm font-medium text-white bg-ink hover:bg-ink/90 transition">
                            Save
                        </button>
                        @if (session('status') === 'profile-updated')
                            <p class="text-xs text-moss font-medium">Saved.</p>
                        @endif
                    </div>
                </form>
            </section>

            <!-- Update Password -->
            <section class="bg-white p-6 rounded-lg border border-rule shadow-sm">
                <h2 class="font-display font-bold text-base text-ink mb-1">Update Password</h2>
                <p class="text-sm text-inksoft mb-6">Use a long, random password to keep your account secure.</p>

                <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    @method('put')

                    <div>
                        <label for="update_password_current_password" class="block font-mono text-xs uppercase tracking-widest text-inksoft mb-1">Current Password</label>
                        <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                            class="w-full rounded border border-rule px-3 py-2 text-sm bg-paper focus:border-ochre focus:ring-1 focus:ring-ochre outline-none">
                        @error('current_password', 'updatePassword')
                            <p class="mt-1 text-xs text-rust">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password" class="block font-mono text-xs uppercase tracking-widest text-inksoft mb-1">New Password</label>
                        <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                            class="w-full rounded border border-rule px-3 py-2 text-sm bg-paper focus:border-ochre focus:ring-1 focus:ring-ochre outline-none">
                        @error('password', 'updatePassword')
                            <p class="mt-1 text-xs text-rust">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password_confirmation" class="block font-mono text-xs uppercase tracking-widest text-inksoft mb-1">Confirm Password</label>
                        <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                            class="w-full rounded border border-rule px-3 py-2 text-sm bg-paper focus:border-ochre focus:ring-1 focus:ring-ochre outline-none">
                        @error('password_confirmation', 'updatePassword')
                            <p class="mt-1 text-xs text-rust">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="inline-flex justify-center px-4 py-2 rounded text-sm font-medium text-white bg-ink hover:bg-ink/90 transition">
                            Save
                        </button>
                        @if (session('status') === 'password-updated')
                            <p class="text-xs text-moss font-medium">Saved.</p>
                        @endif
                    </div>
                </form>
            </section>

            <!-- Delete Account -->
            <section class="bg-white p-6 rounded-lg border border-rust/20 shadow-sm">
                <h2 class="font-display font-bold text-base text-rust mb-1">Delete Account</h2>
                <p class="text-sm text-inksoft mb-6">
                    Once deleted, all of your tasks and categories are permanently removed. This cannot be undone.
                </p>

                <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4" onsubmit="return confirm('This will permanently delete your account and all tasks. Are you sure?');">
                    @csrf
                    @method('delete')

                    <div>
                        <label for="password" class="block font-mono text-xs uppercase tracking-widest text-inksoft mb-1">Confirm Password</label>
                        <input id="password" name="password" type="password" placeholder="Your current password"
                            class="w-full max-w-xs rounded border border-rule px-3 py-2 text-sm bg-paper focus:border-rust focus:ring-1 focus:ring-rust outline-none">
                        @error('password', 'userDeletion')
                            <p class="mt-1 text-xs text-rust">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex justify-center px-4 py-2 rounded text-sm font-medium text-white bg-rust hover:bg-rust/90 transition">
                        Delete Account
                    </button>
                </form>
            </section>

        </div>
    </div>

</body>
</html>