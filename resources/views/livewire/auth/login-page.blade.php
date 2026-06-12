<x-layouts.app>
    <div class="w-full max-w-md space-y-6 rounded-2xl border border-slate-800 bg-slate-900/60 p-8 shadow-2xl">
        <h1 class="text-2xl font-semibold text-white">Sign in</h1>

        @if ($errors->any())
            <div class="rounded-lg border border-rose-400/40 bg-rose-500/10 p-3 text-sm text-rose-200">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
            @csrf
            <label class="block space-y-2 text-sm text-slate-200">
                <span>Email</span>
                <input name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-white" />
            </label>

            <label class="block space-y-2 text-sm text-slate-200">
                <span>Password</span>
                <input name="password" type="password" required class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-white" />
            </label>

            <label class="flex items-center gap-2 text-sm text-slate-300">
                <input type="checkbox" name="remember" value="1" class="rounded border-slate-700 bg-slate-950" />
                <span>Remember me</span>
            </label>

            <button type="submit" class="w-full rounded-lg bg-emerald-500 px-4 py-2 font-medium text-slate-950 hover:bg-emerald-400">Sign in</button>
        </form>

        <a href="{{ route('password.request') }}" class="text-sm text-emerald-300 hover:text-emerald-200">Forgot your password?</a>
    </div>
</x-layouts.app>
