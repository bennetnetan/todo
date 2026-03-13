<x-guest-layout>
    <div class="todo-shell" style="min-height: 100vh;">
        <div class="page-wrap" style="max-width: 720px;">
            <header class="header" role="banner">
                <div class="header-left">
                    <p class="eyebrow">Get started</p>
                    <h1>Create your <em>workspace</em></h1>
                </div>
                <div class="header-actions">
                    <button class="theme-btn" id="theme-toggle" aria-label="Toggle colour theme" title="Toggle light / dark mode">
                        <span id="theme-icon">☀️</span>
                    </button>
                    <a href="{{ route('login') }}" class="btn-secondary">Log in</a>
                </div>
            </header>

            @if ($errors->any())
                <div class="bg-rose-100 text-rose-800 border border-rose-200 rounded-lg px-4 py-3 mb-4 shadow-sm" role="alert">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="form-card">
                @csrf
                <div class="form-grid">
                    <div>
                        <label class="field-label" for="name">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" class="field-input" required autofocus autocomplete="name">
                    </div>
                    <div>
                        <label class="field-label" for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="field-input" required autocomplete="username">
                    </div>
                </div>

                <div class="form-grid" style="margin-top:1rem;">
                    <div>
                        <label class="field-label" for="password">Password</label>
                        <input id="password" name="password" type="password" class="field-input" required autocomplete="new-password">
                    </div>
                    <div>
                        <label class="field-label" for="password_confirmation">Confirm password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="field-input" required autocomplete="new-password">
                    </div>
                </div>

                <div class="form-footer">
                    <a href="{{ route('login') }}" class="btn-secondary">Already registered?</a>
                    <button class="btn-primary" type="submit">Create account</button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
