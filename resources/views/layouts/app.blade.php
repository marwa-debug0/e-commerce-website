<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bauhaus Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bauhaus-white text-bauhaus-black">
    
    {{-- Navbar --}}
    <header class="border-b border-bauhaus-black">
        <div class="max-w-155-units mx-auto px-unit-2 py-unit-1 flex justify-between items-center"> {{--flex box--}}
            <a href="{{route('home') }}" class="font-bold text-xl uppercase tracking-widest"> 
                Bauhaus Store
            </a>
            <nav class="flex gap-unit-2 items-center text-sm uppercase font-bold tracking-wider">
                <a href="{{ route('home') }}">Shop</a>
                @auth
                    <a href="{{route('cart.index')}}">Cart</a>
                    <form method="POST" action="{{route('logout')}}">
                        @csrf 
                        <button type="submit">Logout</button>
                    </form> 
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </nav>
        </div>
    </header>


    {{--Page content--}}
    <main class="max-w-155-units mx-auto px-unit-2 py-unit-2 border-1 border-r border-bauhaus-black min-h-screen">
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
        @isset($slot)
            {{ $slot }}
        @endisset
    </main>

    {{-- Footer --}}
    <footer class="border-t border-bauhaus-black">
        <div class="max-w-155-units mx-auto px-unit-2 py-unit-1 text-sm uppercase tracking-wider">
            © 2024 Bauhaus Store. All rights reserved.
        </div>
    </footer>
    
    
</body>
</html>