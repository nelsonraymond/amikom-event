<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kepanitiaan - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-indigo-600 min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 font-black text-indigo-600 text-xl">AH</div>
            <h1 class="text-2xl font-black text-white">Login Kepanitiaan</h1>
            <p class="text-indigo-100 mt-1">Kelola event dan lihat analitik pendapatanmu.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl p-8">
            @if ($errors->any())
                <div class="mb-6 px-5 py-4 bg-rose-50 text-rose-600 rounded-2xl text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('partner.login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium">
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-500 font-medium">
                    <input type="checkbox" name="remember" class="rounded border-slate-300">
                    Ingat saya
                </label>
                <button type="submit"
                    class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                    Masuk
                </button>
            </form>

            <p class="text-center text-sm text-slate-500 mt-6">
                Belum punya akun Kepanitiaan?
                <a href="{{ route('partner.register') }}" class="text-indigo-600 font-bold hover:underline">Daftar di sini</a>
            </p>
        </div>

        <p class="text-center text-indigo-100 text-sm mt-6">
            <a href="{{ route('home') }}" class="hover:underline">← Kembali ke AmikomEventHub</a>
        </p>
    </div>
</body>
</html>