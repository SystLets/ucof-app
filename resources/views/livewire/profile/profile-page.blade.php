<x-layouts.app>
    <div class="w-full max-w-2xl space-y-6 rounded-2xl border border-slate-800 bg-slate-900/60 p-8 shadow-2xl">
        <div>
            <h1 class="text-2xl font-semibold text-white">Profile</h1>
            <p class="text-sm text-slate-300">View your account details and update your password.</p>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-emerald-400/40 bg-emerald-500/10 p-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg border border-rose-400/40 bg-rose-500/10 p-3 text-sm text-rose-200">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <div class="rounded-lg border border-slate-800 bg-slate-950 p-4">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Name</p>
                <p class="mt-2 text-slate-100">{{ $user?->name }}</p>
            </div>
            <div class="rounded-lg border border-slate-800 bg-slate-950 p-4">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Email</p>
                <p class="mt-2 text-slate-100">{{ $user?->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-4 rounded-lg border border-slate-800 bg-slate-950 p-4">
            @csrf
            <h2 class="text-lg font-semibold text-white">Change password</h2>

            <label class="block space-y-2 text-sm text-slate-200">
                <span>Current password</span>
                <input name="current_password" type="password" required class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white" />
            </label>

            <label class="block space-y-2 text-sm text-slate-200">
                <span>New password</span>
                <input name="password" type="password" required class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white" />
            </label>

            <label class="block space-y-2 text-sm text-slate-200">
                <span>Confirm new password</span>
                <input name="password_confirmation" type="password" required class="w-full rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-white" />
            </label>

            <button type="submit" class="rounded-lg bg-emerald-500 px-4 py-2 font-medium text-slate-950 hover:bg-emerald-400">Update password</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="rounded-lg border border-slate-700 px-4 py-2 text-sm text-slate-200 hover:bg-slate-800">Sign out</button>
        </form>
    </div>
</x-layouts.app>
