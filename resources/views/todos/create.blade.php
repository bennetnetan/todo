@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-gray-800">Create New Task</h2>
        <a href="{{ route('todos.index') }}" class="text-gray-500 hover:text-gray-700 underline text-sm transition">Back to List</a>
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

    <form action="{{ route('todos.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                Task Title <span class="text-red-500">*</span>
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 transition" id="title" name="title" type="text" placeholder="E.g. Buy Groceries" value="{{ old('title') }}" required autofocus>
        </div>
        
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                Description (Optional)
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 transition h-32" id="description" name="description" placeholder="Additional details...">{{ old('description') }}</textarea>
        </div>
        
        <div class="flex items-center justify-end">
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out" type="submit">
                Save Task
            </button>
        </div>
    </form>
@endsection
