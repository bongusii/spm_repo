@extends('layouts.admin')

@section('title', 'Cập nhật Phòng')
@section('header', 'Chỉnh sửa Phòng ' . $room->room_number)

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow">
    <div class="mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold text-gray-800">Cập nhật thông tin</h2>
        <p class="text-sm text-gray-500">Chỉnh sửa trạng thái hoặc loại phòng</p>
    </div>

    <form action="{{ route('rooms.update', $room->id) }}" method="POST">
        @csrf
        @method('PUT') <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Loại Phòng</label>
            <select name="room_type_id" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 bg-white">
                @foreach($roomTypes as $type)
                    <option value="{{ $type->id }}" {{ $room->room_type_id == $type->id ? 'selected' : '' }}>
                        {{ $type->hotel->name }} - {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Số Phòng</label>
            <input type="text" name="room_number" value="{{ $room->room_number }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Trạng thái hiện tại</label>
            <select name="status" class="w-full border rounded px-3 py-2 bg-white">
                <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>Trống (Sẵn sàng)</option>
                <option value="booked" {{ $room->status == 'booked' ? 'selected' : '' }}>Đang có khách</option>
                <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }}>Đang bảo trì</option>
            </select>
        </div>

        <div class="flex items-center justify-between mt-8">
            <a href="{{ route('rooms.index') }}" class="text-gray-600 hover:underline">
                Hủy bỏ
            </a>
            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700 shadow transition">
                Cập nhật
            </button>
        </div>
    </form>
</div>
@endsection