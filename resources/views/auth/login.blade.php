<x-guest-layout>
    <div class="todo-shell" style="min-height: 100vh;">
        <div class="page-wrap" style="max-width: 640px;">
            <header class="header" role="banner">
                <div class="header-left">
                    <p class="eyebrow">Welcome back</p>
                    <h1>Sign in to <em>your</em> tasks</h1>
                </div>
                <div class="header-actions">
                    <button class="theme-btn" id="theme-toggle" aria-label="Toggle colour theme" title="Toggle light / dark mode">
                        <span id="theme-icon">☀️</span>
                    </button>
                    <a href="{{ route('register') }}" class="btn-secondary">Create account</a>
                </div>
            </header>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="form-card space-y-4">
                @csrf

                <div class="form-grid">
                    <div>
                        <label class="field-label" for="email">Email</label>
                        <input id="email" class="field-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <label class="field-label" for="password">Password</label>
                        <input id="password" class="field-input" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 dark:text-slate-300">
                        <input id="remember_me" type="checkbox" class="h-4 w-4" name="remember">
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-indigo-600 dark:text-indigo-300 hover:underline" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <div class="form-footer">
                    <a href="{{ route('register') }}" class="btn-secondary">Need an account?</a>
                    <button class="btn-primary" type="submit">Log in</button>
                </div>

                <div class="form-card" style="margin-top:1rem;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Demo account</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Email: demo@example.com</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Password: password</p>
                        </div>
                        <button type="button" class="btn-secondary" style="padding:.35rem .75rem;" onclick="document.getElementById('email').value='demo@example.com';document.getElementById('password').value='password';document.getElementById('email').focus();">
                            Fill &amp; use
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
