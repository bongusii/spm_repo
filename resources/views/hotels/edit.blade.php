@extends('layouts.admin')

@section('title', 'Cập nhật Khách sạn')
@section('header', 'Chỉnh sửa: ' . $hotel->name)

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('hotels.update', $hotel->id) }}" method="POST">
            @csrf
            @method('PUT') <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tên Khách Sạn</label>
                <input class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       name="name" type="text" value="{{ $hotel->name }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Địa chỉ</label>
                <input class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       name="address" type="text" value="{{ $hotel->address }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Hotline</label>
                <input class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       name="hotline" type="text" value="{{ $hotel->hotline }}" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Mô tả</label>
                <textarea class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                          name="description" rows="3">{{ $hotel->description }}</textarea>
            </div>

            @if(Auth::user()->role === 'super_admin')
            <div class="mb-6 bg-yellow-50 p-3 rounded border border-yellow-200">
                <label class="block text-yellow-800 text-sm font-bold mb-2">Người Quản Lý (Manager)</label>
                <select name="manager_id" class="w-full border rounded py-2 px-3 bg-white">
                    <option value="">-- Chưa gán --</option>
                    @foreach($managers as $manager)
                        <option value="{{ $manager->id }}" {{ $hotel->manager_id == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }} ({{ $manager->email }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-yellow-600 mt-1">* Chỉ Super Admin mới thấy mục này.</p>
            </div>
            @endif

            <div class="flex items-center justify-between">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">
                    Lưu Thay Đổi
                </button>
                <a href="{{ route('hotels.index') }}" class="text-blue-500 hover:text-blue-800 font-bold text-sm">
                    Hủy bỏ
                </a>
            </div>
        </form>
    </div>
@endsection