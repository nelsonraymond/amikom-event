@extends('layouts.app')

@section('title', 'Masuk - AmikomEventHub')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 py-12">
    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="flex items-center justify-center gap-2 mb-8">
            <div class="w-10 h-10 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold">
                AH
            </div>
            <span class="text-lg font-semibold text-gray-900">AmikomEventHub</span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h1 class="text-2xl font-bold text-gray-900 text-center">Masuk ke akunmu</h1>
            <p class="text-sm text-gray-500 text-center mt-1 mb-6">
                Lanjutkan pesan tiket event favoritmu.
            </p>

            {{-- Alert --}}
            @if (session('error'))
                <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg px-4 py-2.5">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg px-4 py-2.5">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Google SSO --}}
            <a href="{{ route('auth.google') }}"
               class="flex items-center justify-center gap-3 w-full border border-gray-300 rounded-lg py-2.5 px-4 hover:bg-gray-50 transition font-medium text-gray-700 text-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M23.49 12.27c0-.79-.07-1.54-.19-2.27H12v4.51h6.47c-.29 1.48-1.14 2.73-2.4 3.58v3h3.86c2.26-2.09 3.56-5.17 3.56-8.82z"/>
                    <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.86-3c-1.08.72-2.45 1.16-4.07 1.16-3.13 0-5.78-2.11-6.73-4.96H1.29v3.09C3.26 21.3 7.31 24 12 24z"/>
                    <path fill="#FBBC05" d="M5.27 14.29c-.25-.72-.38-1.49-.38-2.29s.14-1.57.38-2.29V6.62H1.29A11.96 11.96 0 000 12c0 1.94.46 3.77 1.29 5.38l3.98-3.09z"/>
                    <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.94 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.62l3.98 3.09C6.22 6.86 8.87 4.75 12 4.75z"/>
                </svg>
                Continue with Google
            </a>

            <div class="flex items-center gap-3 my-6">
                <hr class="flex-1 border-gray-200">
                <span class="text-xs text-gray-400 uppercase tracking-wide">atau</span>
                <hr class="flex-1 border-gray-200">
            </div>

            {{-- Form login manual --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="nama@email.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        Ingat saya
                    </label>
                    {{-- Sesuaikan route lupa password kalau sudah ada --}}
                    <a href="#" class="text-indigo-600 hover:underline">Lupa password?</a>
                </div>

                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg py-2.5 transition">
                    Masuk
                </button>
            </form>

            <p class="text-sm text-gray-500 text-center mt-6">
                Belum punya akun?
                <a href="{{ Route::has('register') ? route('register') : '#' }}" class="text-indigo-600 font-medium hover:underline">Daftar</a>
            </p>
        </div>
    </div>
</div>
@endsection