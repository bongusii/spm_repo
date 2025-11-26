@extends('layouts.admin')

@section('title', 'Quản lý Loại phòng')
@section('header', 'Danh sách Hạng phòng')

@section('content')
    <div class="mb-4">
        <a href="{{ route('room-types.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
            + Thêm Loại Phòng Mới
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tên Hạng Phòng</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Khách Sạn</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Giá/Đêm</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sức chứa</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roomTypes as $type)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold">{{ $type->name }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $type->hotel->name }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-green-600 font-bold">
                        {{ number_format($type->price_per_night) }} VNĐ
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $type->capacity }} người</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <a href="#" class="text-blue-600 hover:text-blue-900">Sửa</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection