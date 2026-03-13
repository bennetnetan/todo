@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-gray-800">Edit Task</h2>
        <a href="{{ route('todos.index') }}" class="text-gray-500 hover:text-gray-700 underline text-sm transition">Cancel</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('todos.update', $todo) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                Task Title <span class="text-red-500">*</span>
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 transition" id="title" name="title" type="text" value="{{ old('title', $todo->title) }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="priority">
                Priority (optional)
            </label>
            @php($selected = old('priority', $todo->priority ?? 'none'))
            <select id="priority" name="priority" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 transition">
                <option value="none" {{ $selected === 'none' ? 'selected' : '' }}>None</option>
                <option value="high" {{ $selected === 'high' ? 'selected' : '' }}>High</option>
                <option value="medium" {{ $selected === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="low" {{ $selected === 'low' ? 'selected' : '' }}>Low</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="due_date">
                Due date (optional)
            </label>
            <input id="due_date" type="date" name="due_date" value="{{ old('due_date', optional($todo->due_date)->format('Y-m-d')) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 transition">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Description (Optional)
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 transition h-32" id="description" name="description">{{ old('description', $todo->description) }}</textarea>
        </div>

        <div class="mb-6 border border-gray-200 rounded p-4 bg-gray-50">
            <label class="flex items-center space-x-3 cursor-pointer">
                <input type="checkbox" name="is_completed" value="1" class="form-checkbox h-5 w-5 text-blue-600 transition duration-150 ease-in-out cursor-pointer" {{ old('is_completed', $todo->is_completed) ? 'checked' : '' }}>
                <span class="text-gray-700 font-medium select-none">Mark as completed</span>
            </label>
        </div>
        
        <div class="flex items-center justify-between">
            <form action="{{ route('todos.destroy', $todo) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                <!-- A delete button alongside save can be useful in edit pages -->
            </form>
            
            <div></div> <!-- Spacer -->

            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out" type="submit">
                Update Task
            </button>
        </div>
    </form>
@endsection
