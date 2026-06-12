<x-layouts.app>
    <div class="w-full max-w-md space-y-6 rounded-2xl border border-slate-800 bg-slate-900/60 p-8 shadow-2xl">
        <h1 class="text-2xl font-semibold text-white">Reset password</h1>
        <p class="text-sm text-slate-300">Enter your email and we will generate reset instructions.</p>

        @if (session('status'))
            <div class="rounded-lg border border-emerald-400/40 bg-emerald-500/10 p-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <label class="block space-y-2 text-sm text-slate-200">
                <span>Email</span>
                <input name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-white" />
            </label>

            <button type="submit" class="w-full rounded-lg bg-emerald-500 px-4 py-2 font-medium text-slate-950 hover:bg-emerald-400">Send reset instructions</button>
        </form>

        <a href="{{ route('login') }}" class="text-sm text-emerald-300 hover:text-emerald-200">Back to sign in</a>
    </div>
</x-layouts.app>
