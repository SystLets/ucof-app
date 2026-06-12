<x-layouts.app>
    <div class="w-full max-w-md space-y-6 rounded-2xl border border-slate-800 bg-slate-900/60 p-8 shadow-2xl">
        <h1 class="text-2xl font-semibold text-white">Choose a new password</h1>

        @if ($errors->any())
            <div class="rounded-lg border border-rose-400/40 bg-rose-500/10 p-3 text-sm text-rose-200">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}" />

            <label class="block space-y-2 text-sm text-slate-200">
                <span>New password</span>
                <input name="password" type="password" required class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-white" />
            </label>

            <label class="block space-y-2 text-sm text-slate-200">
                <span>Confirm password</span>
                <input name="password_confirmation" type="password" required class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-white" />
            </label>

            <button type="submit" class="w-full rounded-lg bg-emerald-500 px-4 py-2 font-medium text-slate-950 hover:bg-emerald-400">Update password</button>
        </form>
    </div>
</x-layouts.app>
