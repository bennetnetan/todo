@extends('layouts.app')

@section('content')
    @php
        $openCount = $todos->where('is_completed', false)->count();
        $doneCount = $todos->where('is_completed', true)->count();
    @endphp

    <div class="todo-shell">
        <div class="page-wrap">

            {{-- ── Header ── --}}
            <header class="header" role="banner">
                <div class="header-left">
                    <p class="eyebrow">Task Manager</p>
                    <h1>Stay on top of <em>everything</em></h1>
                </div>
                <div class="header-actions">
                    <button class="theme-btn" id="theme-toggle" aria-label="Toggle colour theme" title="Toggle light / dark mode">
                        <span id="theme-icon">☀️</span>
                    </button>
                    <a href="{{ route('todos.create') }}" class="btn-primary" aria-label="Create a new task">
                        <span aria-hidden="true">＋</span>
                        New Task
                    </a>
                    @auth
                        @include('partials.account-menu')
                    @endauth
                </div>
            </header>

            {{-- ── Stats ── --}}
            <div class="stats-grid" role="region" aria-label="Task summary">
                <div class="stat-card">
                    <p class="stat-label">Open</p>
                    <p class="stat-value amber" id="stat-open">{{ $openCount }}</p>
                    <p class="stat-sub">tasks remaining</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Completed</p>
                    <p class="stat-value emerald" id="stat-done">{{ $doneCount }}</p>
                    <p class="stat-sub">tasks finished</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Total</p>
                    <p class="stat-value accent" id="stat-total">{{ $todos->count() }}</p>
                    <p class="stat-sub">tasks tracked</p>
                </div>
            </div>

            {{-- ── Toolbar (Search + Filter + Sort) ── --}}
            @if ($todos->count())
                <div class="toolbar" role="search" aria-label="Filter and sort tasks">
                    <div class="search-wrap">
                        <span class="search-icon" aria-hidden="true">🔍</span>
                        <input
                            type="search"
                            id="search-input"
                            class="search-input"
                            placeholder="Search tasks…"
                            aria-label="Search tasks"
                            autocomplete="off"
                        >
                    </div>

                    <div class="filter-group" role="group" aria-label="Filter by status">
                        <button class="chip active" data-filter="all"       aria-pressed="true">All</button>
                        <button class="chip"        data-filter="open"      aria-pressed="false">Open</button>
                        <button class="chip"        data-filter="completed" aria-pressed="false">Done</button>
                    </div>

                    <div class="filter-group" role="group" aria-label="Filter by priority">
                        <button class="chip active" data-priority="all"    aria-pressed="true">Any priority</button>
                        <button class="chip"        data-priority="high"   aria-pressed="false">🔴 High</button>
                        <button class="chip"        data-priority="medium" aria-pressed="false">🟡 Medium</button>
                        <button class="chip"        data-priority="low"    aria-pressed="false">🟢 Low</button>
                    </div>

                    <select class="sort-select" id="sort-select" aria-label="Sort tasks">
                        <option value="default">Sort: Default</option>
                        <option value="priority-desc">Priority ↑ High first</option>
                        <option value="priority-asc">Priority ↑ Low first</option>
                        <option value="due-asc">Due date soonest</option>
                        <option value="due-desc">Due date latest</option>
                        <option value="title-asc">Title A → Z</option>
                        <option value="title-desc">Title Z → A</option>
                    </select>
                </div>
            @endif

            {{-- ── Task List --}}
            @if ($todos->count())
                <main>
                    <ul id="task-list" role="list" aria-label="Task list" aria-live="polite">
                        @foreach ($todos as $todo)
                            @php
                                $priority  = $todo->priority  ?? 'none';
                                $dueDate   = $todo->due_date  ?? null;
                                $isOverdue = $dueDate && !$todo->is_completed && \Carbon\Carbon::parse($dueDate)->isPast();
                                $isSoon    = $dueDate && !$todo->is_completed && !$isOverdue && \Carbon\Carbon::parse($dueDate)->diffInDays(now()) <= 2;
                            @endphp

                            <li
                                class="task-card {{ $todo->is_completed ? 'completed' : '' }}"
                                data-status="{{ $todo->is_completed ? 'completed' : 'open' }}"
                                data-priority="{{ $priority }}"
                                data-title="{{ strtolower($todo->title) }}"
                                data-desc="{{ strtolower($todo->description ?? '') }}"
                                data-due="{{ $dueDate }}"
                                data-id="{{ $todo->id }}"
                                draggable="true"
                                aria-label="Task: {{ $todo->title }}"
                            >
                                <span class="drag-handle" aria-hidden="true" title="Drag to reorder">⠿</span>
                                <span class="priority-dot {{ $priority }}" aria-label="Priority: {{ $priority }}"></span>

                                <div class="task-body">
                                    <div class="task-top">
                                        <span class="task-title">{{ $todo->title }}</span>

                                        <span class="badge {{ $todo->is_completed ? 'emerald' : 'amber' }}" aria-label="Status: {{ $todo->is_completed ? 'Completed' : 'In Progress' }}">
                                            <span class="badge-dot" style="background: currentColor;"></span>
                                            {{ $todo->is_completed ? 'Done' : 'In progress' }}
                                        </span>

                                        @if ($priority !== 'none')
                                            <span class="priority-badge {{ $priority }}" aria-label="Priority: {{ ucfirst($priority) }}">
                                                {{ ucfirst($priority) }}
                                            </span>
                                        @endif
                                    </div>

                                    @if ($todo->description)
                                        <p class="task-desc">{{ $todo->description }}</p>
                                    @endif

                                    @if ($dueDate)
                                        <div class="task-meta">
                                            <span class="due-date {{ $isOverdue ? 'overdue' : ($isSoon ? 'soon' : '') }}"
                                                  aria-label="{{ $isOverdue ? 'Overdue' : ($isSoon ? 'Due soon' : 'Due') }}: {{ \Carbon\Carbon::parse($dueDate)->format('M j, Y') }}">
                                                <span aria-hidden="true">{{ $isOverdue ? '⚠️' : '📅' }}</span>
                                                {{ $isOverdue ? 'Overdue · ' : ($isSoon ? 'Soon · ' : '') }}{{ \Carbon\Carbon::parse($dueDate)->format('M j, Y') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <div class="task-actions">
                                    <form action="{{ route('todos.update', $todo) }}" method="POST" aria-label="{{ $todo->is_completed ? 'Mark as incomplete' : 'Mark as complete' }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="title"       value="{{ $todo->title }}">
                                        <input type="hidden" name="description" value="{{ $todo->description }}">
                                        <input type="hidden" name="priority"    value="{{ $priority }}">
                                        <input type="hidden" name="due_date"    value="{{ $dueDate }}">
                                        @if (!$todo->is_completed)
                                            <input type="hidden" name="is_completed" value="1">
                                            <button type="submit" class="act-btn complete">✓ Complete</button>
                                        @else
                                            <input type="hidden" name="is_completed" value="0">
                                            <button type="submit" class="act-btn undo">↩ Undo</button>
                                        @endif
                                    </form>

                                    <a href="{{ route('todos.edit', $todo) }}" class="act-btn edit" aria-label="Edit task: {{ $todo->title }}">
                                        ✎ Edit
                                    </a>

                                    <button type="button"
                                            class="act-btn delete"
                                            data-delete-id="{{ $todo->id }}"
                                            data-delete-title="{{ $todo->title }}"
                                            aria-label="Delete task: {{ $todo->title }}">
                                        ✕ Delete
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div id="no-results" role="status" aria-live="polite">
                        <p>🔍 No tasks match your search or filters.</p>
                    </div>
                </main>
            @else
                <div class="empty-state" role="region" aria-label="No tasks yet">
                    <div class="empty-icon" aria-hidden="true">✦</div>
                    <h2>Nothing here yet</h2>
                    <p>Create your first task to start getting things done.</p>
                    <a href="{{ route('todos.create') }}" class="btn-primary" style="display: inline-flex;">
                        <span aria-hidden="true">＋</span> Add your first task
                    </a>
                </div>
            @endif

        </div>

        {{-- Delete confirmation modal --}}
        <div class="modal-backdrop" id="delete-backdrop" hidden>
            <div class="modal">
                <div class="modal-header">
                    <p class="modal-eyebrow">Confirm deletion</p>
                    <h3 class="modal-title" id="delete-title">Delete task</h3>
                </div>
                <p class="modal-body">This action cannot be undone. Are you sure you want to delete this task?</p>
                <form id="delete-form" method="POST" class="modal-actions">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-secondary" id="delete-cancel">Cancel</button>
                    <button type="submit" class="btn-primary btn-danger">Delete</button>
                </form>
            </div>
        </div>

    </div>
@endsection
