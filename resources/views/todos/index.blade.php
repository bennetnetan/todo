@extends('layouts.app')

@section('content')
    @php
        $openCount = $todos->where('is_completed', false)->count();
        $doneCount = $todos->where('is_completed', true)->count();
    @endphp

    {{-- Google Fonts: Fraunces (display) + Outfit (UI) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;1,9..144,300&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- SortableJS for drag-and-drop --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>

    <style>
        /* ─── CSS Variables ─── */
        :root {
            --bg-base:       #f7f6f2;
            --bg-surface:    #ffffff;
            --bg-surface-2:  #f0efe9;
            --border:        #e4e2d8;
            --border-strong: #c9c6b8;
            --text-primary:  #1a1916;
            --text-secondary:#5a5749;
            --text-muted:    #9c9890;
            --accent:        #6148d5;
            --accent-light:  #ece8ff;
            --accent-hover:  #4f39be;
            --amber:         #d97706;
            --amber-light:   #fef3c7;
            --emerald:       #059669;
            --emerald-light: #d1fae5;
            --rose:          #e11d48;
            --rose-light:    #ffe4e6;
            --shadow-sm:     0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow-md:     0 4px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.04);
            --shadow-lg:     0 8px 32px rgba(0,0,0,.10), 0 2px 8px rgba(0,0,0,.05);
            --radius:        14px;
            --transition:    200ms cubic-bezier(.4,0,.2,1);
        }

        [data-theme="dark"] {
            --bg-base:       #0d0f14;
            --bg-surface:    #161921;
            --bg-surface-2:  #1e2130;
            --border:        #252a38;
            --border-strong: #343b52;
            --text-primary:  #edecea;
            --text-secondary:#9da3b8;
            --text-muted:    #5c6380;
            --accent:        #7c6cf0;
            --accent-light:  #1e1a3d;
            --accent-hover:  #9485f5;
            --amber:         #f59e0b;
            --amber-light:   #2d2000;
            --emerald:       #10b981;
            --emerald-light: #002d1e;
            --rose:          #fb7185;
            --rose-light:    #2d0010;
            --shadow-sm:     0 1px 3px rgba(0,0,0,.3), 0 1px 2px rgba(0,0,0,.2);
            --shadow-md:     0 4px 16px rgba(0,0,0,.4), 0 1px 4px rgba(0,0,0,.25);
            --shadow-lg:     0 8px 32px rgba(0,0,0,.5), 0 2px 8px rgba(0,0,0,.3);
        }

        /* ─── Base ─── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', system-ui, sans-serif;
            background-color: var(--bg-base);
            color: var(--text-primary);
            transition: background-color var(--transition), color var(--transition);
            min-height: 100vh;
        }

        /* ─── Layout ─── */
        .page-wrap {
            max-width: 860px;
            margin: 0 auto;
            padding: 2.5rem 1.25rem 4rem;
        }

        /* ─── Header ─── */
        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .header-left .eyebrow {
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .header-left h1 {
            font-family: 'Fraunces', Georgia, serif;
            font-size: clamp(1.75rem, 4vw, 2.4rem);
            font-weight: 600;
            line-height: 1.15;
            color: var(--text-primary);
            margin-top: .25rem;
        }

        .header-left h1 em {
            font-style: italic;
            color: var(--accent);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: .6rem;
            flex-shrink: 0;
            padding-top: .25rem;
        }

        /* ─── Theme Toggle ─── */
        .theme-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1.5px solid var(--border-strong);
            background: var(--bg-surface);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all var(--transition);
            font-size: 1rem;
        }

        .theme-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
            transform: rotate(20deg);
        }

        /* ─── New Task Button ─── */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .5rem 1.1rem;
            border-radius: 100px;
            font-family: 'Outfit', sans-serif;
            font-size: .82rem;
            font-weight: 600;
            background: var(--accent);
            color: #fff;
            border: none;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 4px 14px color-mix(in srgb, var(--accent) 35%, transparent);
            transition: all var(--transition);
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px color-mix(in srgb, var(--accent) 40%, transparent);
        }

        .btn-primary:active { transform: translateY(0); }

        /* ─── Stats Cards ─── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .75rem;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: var(--bg-surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 1rem 1.1rem;
            box-shadow: var(--shadow-sm);
            transition: all var(--transition);
        }

        .stat-card:hover {
            border-color: var(--border-strong);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .stat-label {
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .stat-value {
            font-family: 'Fraunces', serif;
            font-size: 2rem;
            font-weight: 600;
            line-height: 1.1;
            margin-top: .2rem;
        }

        .stat-value.amber  { color: var(--amber); }
        .stat-value.emerald{ color: var(--emerald); }
        .stat-value.accent { color: var(--accent); }

        .stat-sub {
            font-size: .75rem;
            color: var(--text-muted);
            margin-top: .1rem;
        }

        /* ─── Toolbar ─── */
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 180px;
        }

        .search-icon {
            position: absolute;
            left: .8rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: .9rem;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: .55rem .9rem .55rem 2.2rem;
            border-radius: 100px;
            border: 1.5px solid var(--border);
            background: var(--bg-surface);
            color: var(--text-primary);
            font-family: 'Outfit', sans-serif;
            font-size: .82rem;
            outline: none;
            transition: all var(--transition);
        }

        .search-input::placeholder { color: var(--text-muted); }

        .search-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 15%, transparent);
        }

        /* Filter & Sort Chips */
        .filter-group {
            display: flex;
            gap: .35rem;
            flex-wrap: wrap;
        }

        .chip {
            padding: .4rem .85rem;
            border-radius: 100px;
            border: 1.5px solid var(--border);
            background: var(--bg-surface);
            color: var(--text-secondary);
            font-family: 'Outfit', sans-serif;
            font-size: .75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all var(--transition);
            white-space: nowrap;
            user-select: none;
        }

        .chip:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .chip.active {
            background: var(--accent-light);
            border-color: var(--accent);
            color: var(--accent);
            font-weight: 600;
        }

        .sort-select {
            padding: .4rem .8rem;
            border-radius: 100px;
            border: 1.5px solid var(--border);
            background: var(--bg-surface);
            color: var(--text-secondary);
            font-family: 'Outfit', sans-serif;
            font-size: .75rem;
            font-weight: 500;
            outline: none;
            cursor: pointer;
            transition: all var(--transition);
        }

        .sort-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 15%, transparent);
        }

        /* ─── Task List ─── */
        #task-list {
            display: flex;
            flex-direction: column;
            gap: .6rem;
        }

        /* ─── Task Card ─── */
        .task-card {
            background: var(--bg-surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 1rem 1.1rem;
            box-shadow: var(--shadow-sm);
            transition: all var(--transition);
            display: flex;
            gap: .75rem;
            align-items: flex-start;
            animation: slideIn .25s ease both;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .task-card:hover {
            border-color: var(--border-strong);
            box-shadow: var(--shadow-md);
            transform: translateY(-1px);
        }

        .task-card.completed {
            opacity: .72;
        }

        /* Drag handle */
        .drag-handle {
            cursor: grab;
            color: var(--text-muted);
            font-size: .85rem;
            padding: .15rem .1rem;
            flex-shrink: 0;
            line-height: 1.6;
            opacity: 0;
            transition: opacity var(--transition);
        }

        .task-card:hover .drag-handle { opacity: 1; }

        .task-card.sortable-chosen { box-shadow: var(--shadow-lg); opacity: .9; cursor: grabbing; }
        .task-card.sortable-ghost  { opacity: .35; border-style: dashed; }

        /* Priority dot */
        .priority-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: .55rem;
        }
        .priority-dot.high   { background: var(--rose); box-shadow: 0 0 5px color-mix(in srgb, var(--rose) 50%, transparent); }
        .priority-dot.medium { background: var(--amber); }
        .priority-dot.low    { background: var(--emerald); }
        .priority-dot.none   { background: var(--border-strong); }

        /* Task body */
        .task-body {
            flex: 1;
            min-width: 0;
        }

        .task-top {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .4rem .6rem;
        }

        .task-title {
            font-size: .95rem;
            font-weight: 600;
            color: var(--text-primary);
            line-height: 1.4;
        }

        .task-card.completed .task-title {
            text-decoration: line-through;
            color: var(--text-muted);
        }

        /* Status badge */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .2rem .6rem;
            border-radius: 100px;
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .03em;
        }

        .badge-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
        }

        .badge.amber   { background: var(--amber-light);   color: var(--amber);   border: 1px solid color-mix(in srgb, var(--amber)   25%, transparent); }
        .badge.emerald { background: var(--emerald-light); color: var(--emerald); border: 1px solid color-mix(in srgb, var(--emerald) 25%, transparent); }

        /* Priority badge */
        .priority-badge {
            display: inline-flex;
            align-items: center;
            gap: .25rem;
            padding: .18rem .55rem;
            border-radius: 100px;
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .priority-badge.high   { background: var(--rose-light);    color: var(--rose);    border: 1px solid color-mix(in srgb, var(--rose)    20%, transparent); }
        .priority-badge.medium { background: var(--amber-light);   color: var(--amber);   border: 1px solid color-mix(in srgb, var(--amber)   20%, transparent); }
        .priority-badge.low    { background: var(--emerald-light); color: var(--emerald); border: 1px solid color-mix(in srgb, var(--emerald) 20%, transparent); }

        .task-desc {
            margin-top: .35rem;
            font-size: .82rem;
            color: var(--text-secondary);
            line-height: 1.55;
        }

        .task-card.completed .task-desc {
            text-decoration: line-through;
            color: var(--text-muted);
        }

        /* Meta row (due date etc.) */
        .task-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .5rem;
            margin-top: .5rem;
        }

        .due-date {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            font-size: .72rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .due-date.overdue { color: var(--rose); }
        .due-date.soon    { color: var(--amber); }

        /* Task actions */
        .task-actions {
            display: flex;
            gap: .35rem;
            flex-shrink: 0;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        /* Action buttons */
        .act-btn {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .35rem .75rem;
            border-radius: 100px;
            font-family: 'Outfit', sans-serif;
            font-size: .72rem;
            font-weight: 600;
            border: 1.5px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all var(--transition);
            white-space: nowrap;
        }

        .act-btn.complete {
            border-color: color-mix(in srgb, var(--emerald) 40%, transparent);
            background: var(--emerald-light);
            color: var(--emerald);
        }

        .act-btn.complete:hover {
            background: color-mix(in srgb, var(--emerald) 20%, transparent);
            border-color: var(--emerald);
        }

        .act-btn.undo {
            border-color: var(--border-strong);
            background: var(--bg-surface-2);
            color: var(--text-secondary);
        }

        .act-btn.undo:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .act-btn.edit {
            border-color: color-mix(in srgb, var(--accent) 40%, transparent);
            background: var(--accent-light);
            color: var(--accent);
        }

        .act-btn.edit:hover {
            background: color-mix(in srgb, var(--accent) 20%, transparent);
            border-color: var(--accent);
        }

        .act-btn.delete {
            border-color: color-mix(in srgb, var(--rose) 35%, transparent);
            background: var(--rose-light);
            color: var(--rose);
        }

        .act-btn.delete:hover {
            background: color-mix(in srgb, var(--rose) 20%, transparent);
            border-color: var(--rose);
        }

        /* ─── Empty State ─── */
        .empty-state {
            text-align: center;
            background: var(--bg-surface);
            border: 2px dashed var(--border);
            border-radius: calc(var(--radius) + 4px);
            padding: 3.5rem 2rem;
        }

        .empty-icon {
            font-size: 2.5rem;
            margin-bottom: .75rem;
        }

        .empty-state h2 {
            font-family: 'Fraunces', serif;
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: .4rem;
        }

        .empty-state p {
            font-size: .85rem;
            color: var(--text-muted);
            margin-bottom: 1.5rem;
        }

        /* ─── No Results (search) ─── */
        #no-results {
            display: none;
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--text-muted);
            font-size: .88rem;
        }

        /* ─── Responsive ─── */
        @media (max-width: 540px) {
            .stats-grid   { grid-template-columns: repeat(3, 1fr); gap: .5rem; }
            .stat-card    { padding: .75rem .8rem; }
            .stat-value   { font-size: 1.6rem; }
            .task-card    { flex-wrap: wrap; }
            .task-actions { width: 100%; justify-content: flex-start; }
        }

        /* ─── Focus Accessibility ─── */
        :focus-visible {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
            border-radius: 4px;
        }
    </style>

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
            {{-- Search --}}
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

            {{-- Status Filter --}}
            <div class="filter-group" role="group" aria-label="Filter by status">
                <button class="chip active" data-filter="all"       aria-pressed="true">All</button>
                <button class="chip"        data-filter="open"      aria-pressed="false">Open</button>
                <button class="chip"        data-filter="completed" aria-pressed="false">Done</button>
            </div>

            {{-- Priority Filter --}}
            <div class="filter-group" role="group" aria-label="Filter by priority">
                <button class="chip active" data-priority="all"    aria-pressed="true">Any priority</button>
                <button class="chip"        data-priority="high"   aria-pressed="false">🔴 High</button>
                <button class="chip"        data-priority="medium" aria-pressed="false">🟡 Medium</button>
                <button class="chip"        data-priority="low"    aria-pressed="false">🟢 Low</button>
            </div>

            {{-- Sort --}}
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

        {{-- ── Task List ── --}}
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
                            {{-- Drag handle --}}
                            <span class="drag-handle" aria-hidden="true" title="Drag to reorder">⠿</span>

                            {{-- Priority indicator --}}
                            <span class="priority-dot {{ $priority }}" aria-label="Priority: {{ $priority }}"></span>

                            {{-- Task body --}}
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

                            {{-- Actions --}}
                            <div class="task-actions">
                                {{-- Complete / Undo --}}
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

                                {{-- Edit --}}
                                <a href="{{ route('todos.edit', $todo) }}" class="act-btn edit" aria-label="Edit task: {{ $todo->title }}">
                                    ✎ Edit
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('todos.destroy', $todo) }}" method="POST" aria-label="Delete task: {{ $todo->title }}"
                                      onsubmit="return confirm('Delete \'{{ addslashes($todo->title) }}\'? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="act-btn delete">✕ Delete</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div id="no-results" role="status" aria-live="polite">
                    <p>🔍 No tasks match your search or filters.</p>
                </div>
            </main>

        @else
            {{-- Empty state --}}
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

    <script>
    (() => {
        'use strict';

        /* ── Theme ── */
        const root        = document.documentElement;
        const toggleBtn   = document.getElementById('theme-toggle');
        const themeIcon   = document.getElementById('theme-icon');
        const STORAGE_KEY = 'todo-theme';

        const applyTheme = (dark) => {
            root.setAttribute('data-theme', dark ? 'dark' : 'light');
            themeIcon.textContent = dark ? '🌙' : '☀️';
            localStorage.setItem(STORAGE_KEY, dark ? 'dark' : 'light');
        };

        // Initialise from storage or system preference
        const saved = localStorage.getItem(STORAGE_KEY);
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        applyTheme(saved ? saved === 'dark' : prefersDark);

        toggleBtn.addEventListener('click', () => {
            applyTheme(root.getAttribute('data-theme') !== 'dark');
        });

        /* ── Filter + Search + Sort ── */
        const listEl     = document.getElementById('task-list');
        const noResults  = document.getElementById('no-results');
        if (!listEl) return;

        const cards      = () => Array.from(listEl.querySelectorAll('.task-card'));
        const searchInp  = document.getElementById('search-input');
        const sortSel    = document.getElementById('sort-select');

        let activeFilter   = 'all';
        let activePriority = 'all';

        // Status filter chips
        document.querySelectorAll('[data-filter]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('[data-filter]').forEach(b => {
                    b.classList.remove('active');
                    b.setAttribute('aria-pressed', 'false');
                });
                btn.classList.add('active');
                btn.setAttribute('aria-pressed', 'true');
                activeFilter = btn.dataset.filter;
                applyFilters();
            });
        });

        // Priority filter chips
        document.querySelectorAll('[data-priority]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('[data-priority]').forEach(b => {
                    b.classList.remove('active');
                    b.setAttribute('aria-pressed', 'false');
                });
                btn.classList.add('active');
                btn.setAttribute('aria-pressed', 'true');
                activePriority = btn.dataset.priority;
                applyFilters();
            });
        });

        // Search
        searchInp && searchInp.addEventListener('input', applyFilters);

        // Sort
        sortSel && sortSel.addEventListener('change', applyFilters);

        const PRIORITY_ORDER = { high: 0, medium: 1, low: 2, none: 3 };

        function applyFilters() {
            const q       = searchInp ? searchInp.value.toLowerCase().trim() : '';
            let visible   = [];
            let hidden    = [];

            cards().forEach(card => {
                const status   = card.dataset.status;
                const priority = card.dataset.priority;
                const title    = card.dataset.title;
                const desc     = card.dataset.desc;

                const matchStatus   = activeFilter   === 'all' || status   === activeFilter;
                const matchPriority = activePriority === 'all' || priority === activePriority;
                const matchSearch   = !q || title.includes(q) || desc.includes(q);

                if (matchStatus && matchPriority && matchSearch) {
                    visible.push(card);
                } else {
                    card.style.display = 'none';
                    hidden.push(card);
                }
            });

            // Sort visible cards
            const sortVal = sortSel ? sortSel.value : 'default';
            visible.sort((a, b) => {
                switch (sortVal) {
                    case 'priority-desc': return (PRIORITY_ORDER[a.dataset.priority] ?? 3) - (PRIORITY_ORDER[b.dataset.priority] ?? 3);
                    case 'priority-asc':  return (PRIORITY_ORDER[b.dataset.priority] ?? 3) - (PRIORITY_ORDER[a.dataset.priority] ?? 3);
                    case 'title-asc':     return a.dataset.title.localeCompare(b.dataset.title);
                    case 'title-desc':    return b.dataset.title.localeCompare(a.dataset.title);
                    case 'due-asc': {
                        const da = a.dataset.due || '9999-12-31';
                        const db = b.dataset.due || '9999-12-31';
                        return da.localeCompare(db);
                    }
                    case 'due-desc': {
                        const da = a.dataset.due || '';
                        const db = b.dataset.due || '';
                        return db.localeCompare(da);
                    }
                    default: return 0;
                }
            });

            visible.forEach(card => {
                card.style.display = '';
                listEl.appendChild(card);
            });

            hidden.forEach(card => listEl.appendChild(card));

            // Show/hide no-results
            if (noResults) {
                noResults.style.display = visible.length === 0 ? 'block' : 'none';
            }
        }

        /* ── Drag-and-Drop (SortableJS) ── */
        if (typeof Sortable !== 'undefined' && listEl) {
            Sortable.create(listEl, {
                animation: 180,
                easing:    'cubic-bezier(.4,0,.2,1)',
                handle:    '.drag-handle',
                ghostClass:'sortable-ghost',
                chosenClass:'sortable-chosen',
                onEnd(evt) {
                    // Collect new order and POST to server (optional integration)
                    const order = cards().map(c => c.dataset.id).filter(Boolean);
                    // You can POST `order` to a dedicated reorder endpoint if needed:
                    // fetch('/todos/reorder', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}, body: JSON.stringify({order}) });
                    console.log('New order:', order);
                }
            });
        }
    })();
    </script>
@endsection
