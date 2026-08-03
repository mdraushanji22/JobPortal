<!DOCTYPE html>
<html lang="en" class="{{ session('theme', 'light') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name')) - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme:dark)').matches)) document.documentElement.classList.add('dark');</script>
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100" x-data="themeSwitcher()" x-init="init()">
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center text-xl font-bold text-gray-800 dark:text-white">
                        <x-application-logo class="w-8 h-8 mr-2" />
                        JobPortal
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <button @click="toggle()" class="p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                        <i class="fas" :class="isDark ? 'fa-sun text-yellow-400' : 'fa-moon text-gray-500'"></i>
                    </button>
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Dashboard</a>
                        @elseif(Auth::user()->isEmployer())
                            <a href="{{ route('employer.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Dashboard</a>
                        @else
                            <a href="{{ route('candidate.dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Dashboard</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">@csrf
                            <button type="submit" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Login</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <main>@yield('content')</main>
</body>
</html>
