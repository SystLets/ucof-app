<x-layouts.app>
    <div class="w-full max-w-3xl space-y-6 rounded-2xl border border-slate-800 bg-slate-900/60 p-10 shadow-2xl">
        <p class="text-sm uppercase tracking-[0.4em] text-emerald-300">Dashboard</p>
        <h1 class="text-4xl font-semibold tracking-tight text-white">You are signed in</h1>
        <p class="text-slate-300">Welcome to your authenticated landing page.</p>

        <div class="flex gap-3">
            <a href="{{ route('profile.show') }}" class="rounded-lg bg-emerald-500 px-4 py-2 font-medium text-slate-950 hover:bg-emerald-400">Open profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-lg border border-slate-700 px-4 py-2 text-slate-200 hover:bg-slate-800">Logout</button>
            </form>
        </div>
    </div>
</x-layouts.app>
