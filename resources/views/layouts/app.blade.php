<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Todo App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal text-gray-900">
    <div class="container mx-auto p-8 max-w-3xl">
        <header class="mb-8">
            <h1 class="text-4xl font-extrabold text-blue-600 text-center">My Todo App</h1>
        </header>

        <main class="bg-white shadow-xl rounded-lg p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
