<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Khách Sạn - @yield('title')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#1d4ed8', // Một màu xanh chủ đạo ví dụ
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-slate-800 text-white flex-shrink-0 hidden md:block">
            <div class="p-4 text-xl font-bold border-b border-slate-700">
                Quản lý hệ thống
            </div>
            <nav class="mt-4 px-2 space-y-2">
                <a href="{{ route('hotels.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700 transition">
                    🏨 Quản lý Khách sạn
                </a>
                <a href="{{ route('rooms.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700 transition">
                    🔑 Quản lý Phòng
                </a>
                <a href="{{ route('room-types.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700 transition">
                    🏷 Quản lý Loại phòng
                </a>
                <a href="{{ route('customers.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700 transition">
                    👥 Khách hàng
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700 transition">
                    📅 Đặt phòng
                </a>
                <a href="{{ route('promotions.index') }}" class="block px-4 py-2 rounded hover:bg-slate-700 transition">
                    🎁 Quản lý Khuyến mãi
                </a>
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-slate-700 transition {{ request()->routeIs('admin.dashboard') ? 'bg-slate-700' : '' }}">
                    📊 Thống kê
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-y-auto">
            <header class="bg-white shadow p-4 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-700">@yield('header')</h2>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-red-500 hover:text-red-700 ml-2">Đăng xuất</button>
                </form>
            </header>

            <main class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                        <strong class="font-bold">Thành công!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>