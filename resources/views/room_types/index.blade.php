@extends('layouts.admin')

@section('title', 'Quản lý Loại Phòng')
@section('header', 'Danh sách Hạng Phòng')

@section('content')
    <div class="mb-6">
        <a href="{{ route('room-types.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow flex inline-flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Thêm Loại Phòng Mới
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tên Hạng Phòng</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Khách Sạn</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Giá/Đêm</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tiện ích</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roomTypes as $type)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold text-gray-800">
                        {{ $type->name }}
                        <div class="text-xs text-gray-500 font-normal mt-1">{{ $type->capacity }} người</div>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        {{ $type->hotel->name }}
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-bold text-green-600">
                        {{ number_format($type->price_per_night) }} đ
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-gray-500">
                        @if($type->amenities)
                            <div class="flex flex-wrap gap-1">
                                @foreach(json_decode($type->amenities) as $amenity)
                                    <span class="bg-gray-100 rounded px-2 py-1 text-xs text-gray-600 border border-gray-200">{{ $amenity }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="italic text-xs">Không có</span>
                        @endif
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('room-types.edit', $type->id) }}" class="text-blue-600 hover:text-blue-900 font-bold text-xs flex items-center gap-1">
                                ✏️ Sửa
                            </a>

                            <form action="{{ route('room-types.destroy', $type->id) }}" method="POST" onsubmit="return confirm('CẢNH BÁO: Xóa loại phòng này sẽ ảnh hưởng đến các phòng vật lý và đơn đặt phòng liên quan. Bạn chắc chắn muốn xóa?');">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-xs flex items-center gap-1">
                                    🗑️ Xóa
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                
                @if($roomTypes->isEmpty())
                <tr>
                    <td colspan="5" class="px-5 py-5 text-center text-gray-500 italic">
                        Chưa có loại phòng nào. Hãy thêm mới!
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection