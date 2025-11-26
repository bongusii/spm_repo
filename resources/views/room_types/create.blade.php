@extends('layouts.admin')

@section('title', 'Thêm Loại phòng')
@section('header', 'Thêm Hạng phòng Mới')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <form action="{{ route('room-types.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Thuộc Khách Sạn</label>
                <select name="hotel_id" class="border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tên Hạng Phòng</label>
                <input type="text" name="name" class="border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="VD: Deluxe King Ocean View">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Giá mỗi đêm (VNĐ)</label>
                <input type="number" name="price_per_night" class="border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="500000">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Số người tối đa</label>
                <input type="number" name="capacity" class="border rounded w-full py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" value="2">
            </div>
        </div>

        <div class="mb-6 mt-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tiện ích đi kèm</label>
            <div class="flex gap-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="amenities[]" value="wifi" class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="ml-2">Wifi miễn phí</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="amenities[]" value="breakfast" class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="ml-2">Bữa sáng</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="amenities[]" value="ac" class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="ml-2">Điều hòa</span>
                </label>
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Lưu Loại Phòng</button>
    </form>
</div>
@endsection