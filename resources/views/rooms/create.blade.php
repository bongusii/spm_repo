@extends('layouts.admin')

@section('title', 'Thêm Phòng')
@section('header', 'Tạo Phòng Mới (Room)')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow">
    <div class="mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold text-gray-800">Thêm Phòng Mới</h2>
        <p class="text-sm text-gray-500">Nhập thông tin phòng vật lý (Số phòng, trạng thái)</p>
    </div>

    <form action="{{ route('rooms.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Loại Phòng (Hạng Phòng)</label>
            <select name="room_type_id" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 bg-white">
                @foreach($roomTypes as $type)
                    <option value="{{ $type->id }}">
                        {{ $type->hotel->name }} - {{ $type->name }} ({{ number_format($type->price_per_night) }}đ)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Số Phòng (Room Number)</label>
            <input type="text" name="room_number" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="VD: 101, 205A" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Trạng thái ban đầu</label>
            <select name="status" class="w-full border rounded px-3 py-2 bg-white">
                <option value="available">Trống (Sẵn sàng đón khách)</option>
                <option value="maintenance">Đang bảo trì</option>
            </select>
        </div>

        <div class="flex items-center justify-between mt-8">
            <a href="{{ route('rooms.index') }}" class="text-gray-600 hover:underline">
                ← Quay lại danh sách
            </a>
            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700 shadow transition">
                Lưu Phòng
            </button>
        </div>
    </form>
</div>
@endsection