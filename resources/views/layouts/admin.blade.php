<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bauhaus Store — Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bauhaus-white text-bauhaus-black flex flex-col min-h-screen">
    
    {{-- Admin Header --}}
    <header class="border-b border-bauhaus-black bg-bauhaus-black text-bauhaus-white">
        <div class="max-w-155-units mx-auto px-unit-2 py-unit-1 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="font-bold text-xl uppercase tracking-widest hover:text-bauhaus-yellow transition-colors"> 
                    Bauhaus Store
                </a>
                <span class="border-l border-bauhaus-white h-5"></span>
                <span class="text-xs font-black uppercase tracking-widest text-bauhaus-yellow">Control Board</span>
            </div>
            
            <nav class="flex gap-unit-2 items-center text-xs uppercase font-bold tracking-wider">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-bauhaus-yellow transition-colors">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="hover:text-bauhaus-yellow transition-colors">Products Catalog</a>
                <a href="{{ route('home') }}" class="hover:text-bauhaus-yellow transition-colors">Shop View</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf 
                    <button type="submit" class="hover:text-bauhaus-red transition-colors font-bold uppercase text-xs">Logout</button>
                </form> 
            </nav>
        </div>
    </header>

    {{-- Main Area --}}
    <main class="flex-grow max-w-155-units w-full mx-auto px-unit-2 py-unit-2">
        
        {{-- Flash Alerts --}}
        @if(session('success'))
            <div class="mb-unit-2 border border-bauhaus-black bg-bauhaus-white text-bauhaus-black px-unit-1 py-3 text-xs font-black uppercase tracking-wider flex items-center gap-2">
                <span class="w-4 h-4 bg-bauhaus-black text-bauhaus-white flex items-center justify-center text-3xs font-black">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-unit-2 border border-bauhaus-black bg-bauhaus-white text-bauhaus-red px-unit-1 py-3 text-xs font-black uppercase tracking-wider flex items-center gap-2">
                <span class="w-4 h-4 bg-bauhaus-black text-bauhaus-white flex items-center justify-center text-3xs font-black">!</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-bauhaus-black bg-bauhaus-gray bg-opacity-20 mt-unit-2">
        <div class="max-w-155-units mx-auto px-unit-2 py-unit-1 text-xs uppercase tracking-wider text-gray-500 text-center sm:text-left">
            © 2026 Bauhaus Control Console. System Operational.
        </div>
    </footer>
    
</body>
</html>
