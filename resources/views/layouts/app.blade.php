<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AmikomEventHub - Temukan Event Seru!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
        }
    </style>
     @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body class="bg-slate-50 text-slate-900">

    <!-- Navigation -->
    <nav
        class="glass sticky top-8 z-40 mx-4 mt-4 px-6 py-4 rounded-2xl border border-white/20 shadow-lg flex justify-between items-center">
        <div class="flex items-center gap-2">
            <div
                class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                AH</div>
            <span class="text-xl font-bold tracking-tight">AmikomEventHub</span>
        </div>
        <div class="hidden md:flex gap-8 font-medium">
            <a href="/katalog" class="text-indigo-600">Jelajahi</a>
            <a href="/katalog" class="hover:text-indigo-600 transition">Kategori</a>
            <a href="/contact" class="hover:text-indigo-600 transition">Tentang Kami</a>
            <a href="/profil" class="hover:text-indigo-600 transition">Profil</a>
            <a href="/my-ticket" class="hover:text-indigo-600 transition">Tiketku</a>
        </div>
        @guest
        <div class="flex gap-3">
            <a href="{{ route('login') }}"
               class="px-5 py-2.5 rounded-xl font-semibold hover:bg-slate-200 transition">Login</a>
            <a href="{{ route('login') }}"
               class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">Daftar</a>
        </div>
        @else
        <div class="relative group">
            <button class="flex items-center gap-2.5 pl-2 pr-4 py-2 rounded-xl hover:bg-slate-100 transition">
                @if(auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}"
                         class="w-8 h-8 rounded-full object-cover">
                @else
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <span class="font-medium text-sm hidden lg:inline">{{ auth()->user()->name }}</span>
            </button>

            <div class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-2
                        opacity-0 invisible group-hover:opacity-100 group-hover:visible transition z-50">
                <a href="{{ route('ticket') }}" class="block px-4 py-2 text-sm hover:bg-slate-50">Tiketku</a>
                <a href="{{ route('profil') }}" class="block px-4 py-2 text-sm hover:bg-slate-50">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
        @endguest
    </nav>
    
    
    @yield ('content')
    <!-- Footer -->
    <footer class="bg-indigo-900 text-indigo-100 py-20 px-6 mt-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="space-y-4 col-span-1">
                <div class="flex items-center gap-2">
                    <div
                        class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl">
                        AH</div>
                    <span class="text-2xl font-bold text-white">AmikomEventHub</span>
                </div>
                <p class="max-w-xs text-indigo-300">Platform reservasi tiket event online terbaik untuk mahasiswa dan
                    penyelenggara profesional.</p>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Kategori</h4> 
                <ul class="space-y-4">
                @foreach($categories as $cat)
                 <li> <a href="/?category={{ $cat->slug }}" class="hover:text-white transition">
                {{ $cat->name }}
            </a></li>
        @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-6">Navigasi</h4>
                <ul class="space-y-4">
                    <li><a href="/" class="hover:text-white transition">Home</a></li>
                    <li><a href="/katalog" class="hover:text-white transition">Semua Event</a></li>
                    <li><a href="/bantuan" class="hover:text-white transition">Cara Bayar</a></li>
                </ul>
            </div>
           
            <div>
                <h4 class="text-white font-bold mb-6">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li><a href="/contact" class="hover:text-white transition">Kontak Kami</a></li>
                    <li>support@eventtiket.com</li>
                    <li>+62 812 3456 7890</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto pt-12 mt-12 border-t border-indigo-800 text-center text-indigo-400 text-sm">
            &copy; 2026 AmikomEventHub. Built with Laravel & Tailwind CSS.
        </div>
    </footer>

</body>

</html>