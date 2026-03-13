<div class="account-menu" data-open="false">
    <button type="button" class="btn-secondary" id="account-menu-toggle">
        <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ Auth::user()->name ?? 'Account' }}</span>
    </button>
    <div class="menu-panel" id="account-menu-panel" hidden>
        <div class="text-sm text-slate-600 dark:text-slate-300 mb-2">
            <div class="font-semibold">{{ Auth::user()->name ?? '' }}</div>
            <div>{{ Auth::user()->email ?? '' }}</div>
        </div>
        <div class="menu-actions">
            <a class="menu-link" href="{{ route('profile.edit') }}">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="menu-link text-rose-600">Log out</button>
            </form>
        </div>
    </div>
</div>
