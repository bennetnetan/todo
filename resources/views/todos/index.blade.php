@extends('layouts.app')

@section('content')
    @php
        $openCount = $todos->where('is_completed', false)->count();
        $doneCount = $todos->where('is_completed', true)->count();
    @endphp

    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100">
        <div class="max-w-5xl mx-auto px-4 py-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.12em] text-slate-400 font-semibold">Todo Dashboard</p>
                    <h1 class="text-3xl font-semibold mt-1 text-white">Stay on top of your tasks</h1>
                </div>
                <a href="{{ route('todos.create') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-indigo-500 px-4 py-2 text-sm font-semibold shadow-lg shadow-indigo-500/25 hover:bg-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <span class="text-lg leading-none">＋</span>
                    New Task
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-4 shadow-lg shadow-black/30">
                    <p class="text-xs uppercase tracking-[0.1em] text-slate-400">Open</p>
                    <div class="mt-1 flex items-baseline gap-2">
                        <span class="text-3xl font-semibold text-amber-300">{{ $openCount }}</span>
                        <span class="text-sm text-slate-400">tasks</span>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-4 shadow-lg shadow-black/30">
                    <p class="text-xs uppercase tracking-[0.1em] text-slate-400">Completed</p>
                    <div class="mt-1 flex items-baseline gap-2">
                        <span class="text-3xl font-semibold text-emerald-300">{{ $doneCount }}</span>
                        <span class="text-sm text-slate-400">tasks</span>
                    </div>
                </div>
                <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-4 shadow-lg shadow-black/30">
                    <p class="text-xs uppercase tracking-[0.1em] text-slate-400">Total</p>
                    <div class="mt-1 flex items-baseline gap-2">
                        <span class="text-3xl font-semibold text-indigo-200">{{ $todos->count() }}</span>
                        <span class="text-sm text-slate-400">tasks</span>
                    </div>
                </div>
            </div>

            @if ($todos->count())
                <div class="space-y-4">
                    @foreach ($todos as $todo)
                        <article class="group rounded-2xl border border-slate-800 bg-slate-900/70 p-5 shadow-lg shadow-black/30 transition hover:-translate-y-0.5 hover:border-indigo-500/70 hover:shadow-indigo-500/20">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-3">
                                        <h2 class="text-lg font-semibold {{ $todo->is_completed ? 'line-through text-slate-500' : 'text-white' }}">
                                            {{ $todo->title }}
                                        </h2>
                                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold
                                            {{ $todo->is_completed ? 'bg-emerald-500/15 text-emerald-200 border border-emerald-500/30' : 'bg-amber-500/15 text-amber-200 border border-amber-500/30' }}">
                                            <span class="h-2 w-2 rounded-full {{ $todo->is_completed ? 'bg-emerald-400' : 'bg-amber-300' }}"></span>
                                            {{ $todo->is_completed ? 'Completed' : 'In Progress' }}
                                        </span>
                                    </div>
                                    @if ($todo->description)
                                        <p class="text-sm leading-relaxed {{ $todo->is_completed ? 'text-slate-500 line-through' : 'text-slate-200' }}">
                                            {{ $todo->description }}
                                        </p>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-2 sm:justify-end">
                                    <form action="{{ route('todos.update', $todo) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="title" value="{{ $todo->title }}">
                                        <input type="hidden" name="description" value="{{ $todo->description }}">
                                        @if(!$todo->is_completed)
                                            <input type="hidden" name="is_completed" value="1">
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 rounded-full border border-emerald-400/60 bg-emerald-500/15 px-3 py-1.5 text-xs font-semibold text-emerald-200 hover:bg-emerald-500/25 focus:outline-none focus:ring-2 focus:ring-emerald-300/60">
                                                Complete
                                            </button>
                                        @else
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 rounded-full border border-slate-500/60 bg-slate-700/40 px-3 py-1.5 text-xs font-semibold text-slate-100 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400/60">
                                                Undo
                                            </button>
                                        @endif
                                    </form>

                                    <a href="{{ route('todos.edit', $todo) }}"
                                       class="inline-flex items-center gap-1 rounded-full border border-indigo-400/60 bg-indigo-500/15 px-3 py-1.5 text-xs font-semibold text-indigo-100 hover:bg-indigo-500/25 focus:outline-none focus:ring-2 focus:ring-indigo-300/60">
                                        Edit
                                    </a>

                                    <form action="{{ route('todos.destroy', $todo) }}" method="POST" class="inline" onsubmit="return confirm('Delete this task?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-full border border-rose-400/60 bg-rose-500/15 px-3 py-1.5 text-xs font-semibold text-rose-100 hover:bg-rose-500/25 focus:outline-none focus:ring-2 focus:ring-rose-300/60">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-900/40 p-10 text-center text-slate-300 shadow-inner shadow-black/20">
                    <p class="text-xl font-semibold text-white mb-2">No tasks yet</p>
                    <p class="text-sm text-slate-400 mb-6">Create your first task to get started.</p>
                    <a href="{{ route('todos.create') }}"
                       class="inline-flex items-center gap-2 rounded-full bg-indigo-500 px-4 py-2 text-sm font-semibold shadow-lg shadow-indigo-500/25 hover:bg-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        Add a task
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
