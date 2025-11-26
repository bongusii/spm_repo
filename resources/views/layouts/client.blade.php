<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <nav class="bg-white shadow-md fixed w-full z-10 top-0">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a class="font-bold text-2xl text-blue-600" href="/">BongusiStay</a>
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 mr-4 font-semibold">Trang chủ</a>
                
                @guest
                    {{-- Nếu CHƯA đăng nhập --}}
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Đăng ký</a>
                @endguest

                @auth
                    <a href="{{ route('my-bookings.index') }}" class="text-blue-600 hover:text-blue-800 mr-4 font-semibold">
                        Lịch sử đơn
                    </a>

                    {{-- Nếu ĐÃ đăng nhập --}}
                    <span class="text-gray-800 font-bold">Hi, {{ Auth::user()->name }}</span>
                    
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-700 ml-2">Đăng xuất</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <div class="pt-20 min-h-screen">
        @yield('content')
    </div>

    <footer class="bg-slate-800 text-white py-8 mt-12">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 BongusiStay. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>