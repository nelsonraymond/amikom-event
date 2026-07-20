<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Kepanitiaan') - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">
<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-72 bg-white border-r border-slate-100 flex flex-col fixed h-screen">
        <div class="p-8 border-b border-slate-100">
            <a href="{{ route('partner.dashboard') }}" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold">AH</div>
                <div>
                    <p class="font-extrabold leading-none">AmikomEventHub</p>
                    <p class="text-xs text-slate-400 font-medium">Panel Kepanitiaan</p>
                </div>
            </a>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('partner.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-sm transition
                      {{ request()->routeIs('partner.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('partner.events.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-sm transition
                      {{ request()->routeIs('partner.events.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Event Saya
            </a>
        </nav>

        <div class="p-4 border-t border-slate-100">
            <div class="flex items-center gap-3 px-4 py-3 mb-2">
                <div class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-sm overflow-hidden">
                    @if (auth('partner')->user()->logo_url)
                        <img src="{{ auth('partner')->user()->logo_url }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(auth('partner')->user()->name, 0, 2)) }}
                    @endif
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-sm truncate">{{ auth('partner')->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ auth('partner')->user()->email }}</p>
                </div>
            </div>
            <form action="{{ route('partner.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl font-bold text-sm text-rose-500 hover:bg-rose-50 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <main class="flex-1 ml-72 p-10">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-900">@yield('page_title')</h1>
            @hasSection('page_subtitle')
                <p class="text-slate-500 mt-1">@yield('page_subtitle')</p>
            @endif
        </div>

        @if (session('success'))
            <div class="mb-6 px-6 py-4 bg-green-50 text-green-700 rounded-2xl font-bold text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 px-6 py-4 bg-rose-50 text-rose-700 rounded-2xl font-bold text-sm">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>
</body>
</html>