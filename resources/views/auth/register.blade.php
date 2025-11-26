@extends('layouts.client')
@section('title', 'Đăng ký thành viên')

@section('content')
<div class="flex justify-center items-center min-h-[80vh] bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Đăng Ký Tài Khoản</h2>
        
        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Họ tên</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required placeholder="Nguyễn Văn A">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required placeholder="email@example.com">
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Số điện thoại</label>
                <input type="text" name="phone" class="w-full border rounded px-3 py-2" placeholder="0912...">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Mật khẩu</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nhập lại mật khẩu</label>
                <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700 transition">
                Đăng Ký Ngay
            </button>
        </form>
        <p class="mt-4 text-center text-sm">Đã có tài khoản? <a href="{{ route('login') }}" class="text-blue-500 font-bold">Đăng nhập</a></p>
    </div>
</div>
@endsection