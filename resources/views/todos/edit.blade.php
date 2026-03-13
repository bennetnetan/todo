@extends('layouts.app')

@section('content')
    <div class="todo-shell">
        <div class="page-wrap">
            <header class="header" role="banner">
                <div class="header-left">
                    <p class="eyebrow">Edit Task</p>
                    <h1>Refine your <em>{{ $todo->title }}</em></h1>
                </div>
                <div class="header-actions">
                    <button class="theme-btn" id="theme-toggle" aria-label="Toggle colour theme" title="Toggle light / dark mode">
                        <span id="theme-icon">☀️</span>
                    </button>
                    <a href="{{ route('todos.index') }}" class="btn-secondary">← Back to list</a>
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

            <form action="{{ route('todos.update', $todo) }}" method="POST" class="form-card">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div>
                        <label class="field-label" for="title">Task title *</label>
                        <input id="title" name="title" type="text" value="{{ old('title', $todo->title) }}" class="field-input" required>
                    </div>

                    <div>
                        <label class="field-label" for="priority">Priority</label>
                        @php($selected = old('priority', $todo->priority ?? 'none'))
                        <select id="priority" name="priority" class="field-select">
                            <option value="none" {{ $selected === 'none' ? 'selected' : '' }}>None</option>
                            <option value="high" {{ $selected === 'high' ? 'selected' : '' }}>High</option>
                            <option value="medium" {{ $selected === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="low" {{ $selected === 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>

                    <div>
                        <label class="field-label" for="due_date">Due date</label>
                        <input id="due_date" type="date" name="due_date" value="{{ old('due_date', optional($todo->due_date)->format('Y-m-d')) }}" class="field-input">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="field-label" for="description">Description</label>
                    <textarea id="description" name="description" class="field-textarea" placeholder="Add context or steps...">{{ old('description', $todo->description) }}</textarea>
                </div>

                <div class="form-card" style="margin-top:1rem;">
                    <label class="flex items-center gap-3 text-sm font-semibold text-gray-700 dark:text-gray-200">
                        <input type="checkbox" name="is_completed" value="1" class="h-5 w-5" {{ old('is_completed', $todo->is_completed) ? 'checked' : '' }}>
                        Mark as completed
                    </label>
                </div>

                <div class="form-footer">
                    <a href="{{ route('todos.index') }}" class="btn-secondary">Cancel</a>
                    <button class="btn-primary" type="submit">Save changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection
