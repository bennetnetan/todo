<x-guest-layout>
    <div class="todo-shell" style="min-height: 100vh;">
        <div class="page-wrap" style="max-width: 640px;">
            <header class="header" role="banner">
                <div class="header-left">
                    <p class="eyebrow">Reset password</p>
                    <h1>Choose a <em>new</em> password</h1>
                </div>
                <div class="header-actions">
                    <button class="theme-btn" id="theme-toggle" aria-label="Toggle colour theme" title="Toggle light / dark mode">
                        <span id="theme-icon">☀️</span>
                    </button>
                    <a href="{{ route('login') }}" class="btn-secondary">Back to login</a>
                </div>
            </header>

            <form method="POST" action="{{ route('password.store') }}" class="form-card space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label class="field-label" for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" class="field-input" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="form-grid">
                    <div>
                        <label class="field-label" for="password">New password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password" class="field-input" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div>
                        <label class="field-label" for="password_confirmation">Confirm password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="field-input" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <div class="form-footer">
                    <a href="{{ route('login') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Reset password</button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
