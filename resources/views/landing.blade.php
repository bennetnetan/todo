@extends('layouts.guest')

@section('content')
    <div class="todo-shell">
        <div class="page-wrap" style="max-width: 1040px;">
            <header class="header" role="banner">
                <div class="header-left">
                    <p class="eyebrow">Todo Demo</p>
                    <h1>Organize work with <em>clarity</em></h1>
                    <p class="text-sm text-slate-500 dark:text-slate-300 mt-2" style="max-width: 540px;">
                        A focused todo app with priorities, due dates, drag-and-drop ordering, and an accessible light/dark experience.
                    </p>
                </div>
                <div class="header-actions">
                    <button class="theme-btn" id="theme-toggle" aria-label="Toggle colour theme" title="Toggle light / dark mode">
                        <span id="theme-icon">☀️</span>
                    </button>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('todos.index') }}" class="btn-primary">Open app</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-secondary">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-primary">Get started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </header>

            <div class="stats-grid" role="region" aria-label="What you get">
                <div class="stat-card">
                    <p class="stat-label">Themes</p>
                    <p class="stat-value accent">Light/Dark</p>
                    <p class="stat-sub">Persisted per user with graceful defaults.</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Focus</p>
                    <p class="stat-value amber">Filters</p>
                    <p class="stat-sub">Search, status, priority, and sort controls.</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Flow</p>
                    <p class="stat-value emerald">Drag</p>
                    <p class="stat-sub">Reorder tasks with keyboard/drag hints.</p>
                </div>
            </div>

            <div class="form-card" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1rem;">
                <div>
                    <h3 class="modal-title">Why it works</h3>
                    <p class="modal-body" style="margin-bottom:0;">
                        Built with Laravel 12 + Breeze, priority and due-date support, and an accessible UI that keeps todos readable in any environment.
                    </p>
                </div>
                <div>
                    <h3 class="modal-title">Quick start</h3>
                    <ul class="text-sm text-slate-600 dark:text-slate-300 space-y-2">
                        <li>Use the demo login on the auth screen: <strong>demo@example.com</strong> / <strong>password</strong>.</li>
                        <li>Toggle the theme in the header; your choice is remembered.</li>
                        <li>Filter by status/priority, sort by priority or due date, and drag to reorder.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
