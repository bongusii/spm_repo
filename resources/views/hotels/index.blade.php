@extends('layouts.admin')

@section('title', 'Danh sách chi nhánh')
@section('header', 'Danh sách các khách sạn trong chuỗi')

@section('content')
    <div class="mb-4">
        <a href="{{ route('hotels.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
            + Thêm Chi Nhánh Mới
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Tên Chi Nhánh
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Địa chỉ
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Hotline
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Hành động
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($hotels as $hotel)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap font-bold">{{ $hotel->name }}</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ $hotel->address }}</p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                            <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                            <span class="relative">{{ $hotel->hotline }}</span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('hotels.edit', $hotel->id) }}" class="text-blue-600 hover:text-blue-900 font-bold">
                                ✏️ Sửa
                            </a>

                            @if(Auth::user()->role === 'super_admin')
                                <form action="{{ route('hotels.destroy', $hotel->id) }}" method="POST" onsubmit="return confirm('CẢNH BÁO: Xóa khách sạn này sẽ xóa toàn bộ Phòng và Đơn hàng liên quan. Bạn chắc chứ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-bold">
                                        🗑️ Xóa
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                
                @if($hotels->isEmpty())
                <tr>
                    <td colspan="4" class="px-5 py-5 text-center text-gray-500">Chưa có chi nhánh nào.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection