<x-guest-layout>
    <div class="todo-shell" style="min-height: 100vh;">
        <div class="page-wrap" style="max-width: 720px;">
            <header class="header" role="banner">
                <div class="header-left">
                    <p class="eyebrow">New Task</p>
                    <h1>Create a <em>fresh</em> todo</h1>
                </div>
                <div class="header-actions">
                    <button class="theme-btn" id="theme-toggle" aria-label="Toggle colour theme" title="Toggle light / dark mode">
                        <span id="theme-icon">☀️</span>
                    </button>
                    <a href="{{ route('todos.index') }}" class="btn-secondary">← Back to list</a>
                    @auth
                        @include('partials.account-menu')
                    @endauth
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

            <form action="{{ route('todos.store') }}" method="POST" class="form-card">
                @csrf

                <div class="form-grid">
                    <div>
                        <label class="field-label" for="title">Task title *</label>
                        <input id="title" name="title" type="text" value="{{ old('title') }}" class="field-input" placeholder="E.g. Buy groceries" required autofocus>
                    </div>

                    <div>
                        <label class="field-label" for="priority">Priority</label>
                        <select id="priority" name="priority" class="field-select">
                            <option value="none" {{ old('priority', 'none') === 'none' ? 'selected' : '' }}>None</option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>

                    <div>
                        <label class="field-label" for="due_date">Due date</label>
                        <input id="due_date" type="date" name="due_date" value="{{ old('due_date') }}" class="field-input">
                    </div>
                </div>

                <div class="mt-4">
                    <label class="field-label" for="description">Description</label>
                    <textarea id="description" name="description" class="field-textarea" placeholder="Add context or steps...">{{ old('description') }}</textarea>
                </div>

                <div class="form-footer">
                    <a href="{{ route('todos.index') }}" class="btn-secondary">Cancel</a>
                    <button class="btn-primary" type="submit">
                        <span aria-hidden="true">＋</span> Save task
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
