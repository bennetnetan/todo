<x-guest-layout>
    <div class="todo-shell" style="min-height: 100vh;">
        <div class="page-wrap" style="max-width: 640px;">
            <header class="header" role="banner">
                <div class="header-left">
                    <p class="eyebrow">Recover access</p>
                    <h1>Send a reset link</h1>
                </div>
                <div class="header-actions">
                    <button class="theme-btn" id="theme-toggle" aria-label="Toggle colour theme" title="Toggle light / dark mode">
                        <span id="theme-icon">☀️</span>
                    </button>
                    <a href="{{ route('login') }}" class="btn-secondary">Back to login</a>
                </div>
            </header>

            <div class="form-card">
                <p class="modal-body" style="margin-bottom:1rem;">Enter your email and we will send you a password reset link.</p>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="field-label" for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="field-input" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="form-footer">
                        <a href="{{ route('login') }}" class="btn-secondary">Cancel</a>
                        <button type="submit" class="btn-primary">Send link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
