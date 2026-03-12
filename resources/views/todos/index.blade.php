@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">Todo List</h2>
        <a href="{{ route('todos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
            + New Task
        </a>
    </div>

    @if ($todos->count())
        <ul class="divide-y divide-gray-200">
            @foreach ($todos as $todo)
                <li class="py-4 flex items-center justify-between">
                    <div class="flex flex-col">
                         <span class="text-lg {{ $todo->is_completed ? 'line-through text-gray-400' : 'text-gray-800 font-medium' }}">
                             {{ $todo->title }}
                         </span>
                         @if($todo->description)
                            <span class="text-sm text-gray-500 mt-1 {{ $todo->is_completed ? 'line-through' : '' }}">{{ $todo->description }}</span>
                         @endif
                    </div>
                    
                    <div class="flex space-x-2">
                        <!-- Update Complete Status Form -->
                        <form action="{{ route('todos.update', $todo) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="title" value="{{ $todo->title }}">
                            <input type="hidden" name="description" value="{{ $todo->description }}">
                            @if(!$todo->is_completed)
                                <input type="hidden" name="is_completed" value="1">
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded text-sm transition focus:outline-none">Complete</button>
                            @else
                                <!-- If we wanted to un-complete it, we omit is_completed -->
                                <button type="submit" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-1 px-3 rounded text-sm transition focus:outline-none">Undo</button>
                            @endif
                        </form>
                        
                        <a href="{{ route('todos.edit', $todo) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-sm transition focus:outline-none">Edit</a>
                        
                        <!-- Delete Form -->
                        <form action="{{ route('todos.destroy', $todo) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-sm transition focus:outline-none">Delete</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="text-center py-8 text-gray-500">
            <p class="text-xl mb-4">You have no tasks yet!</p>
            <p>Click the button above to add one.</p>
        </div>
    @endif
@endsection
