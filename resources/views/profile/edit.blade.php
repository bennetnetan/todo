<x-app-layout>
    <div class="todo-shell" style="min-height: 100vh;">
        <div class="page-wrap" style="max-width: 900px;">
            <header class="header" role="banner">
                <div class="header-left">
                    <p class="eyebrow">Account</p>
                    <h1>Manage your <em>profile</em></h1>
                </div>
                <div class="header-actions">
                    <a href="{{ route('dashboard') }}" class="btn-secondary">← Dashboard</a>
                    <button class="theme-btn" id="theme-toggle" aria-label="Toggle colour theme" title="Toggle light / dark mode">
                        <span id="theme-icon">☀️</span>
                    </button>
                    @auth
                        @include('partials.account-menu')
                    @endauth
                </div>
            </header>

            <div class="space-y-6">
                <div class="form-card">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="form-card">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="form-card">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
