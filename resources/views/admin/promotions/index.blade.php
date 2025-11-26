@extends('layouts.admin')

@section('title', 'Quản lý Khuyến mãi')
@section('header', 'Danh sách Mã Giảm Giá')

@section('content')
    <div class="mb-4">
        <a href="{{ route('promotions.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
            + Tạo Mã Mới
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Mã Code</th>
                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Mức giảm</th>
                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Hiệu lực</th>
                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Trạng thái</th>
                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($promotions as $promo)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-5 border-b bg-white text-sm font-bold text-blue-600">
                        {{ $promo->code }}
                    </td>
                    <td class="px-5 py-5 border-b bg-white text-sm font-bold text-green-600">
                        @if($promo->discount_percent)
                            Giảm {{ $promo->discount_percent }}%
                        @else
                            Giảm {{ number_format($promo->discount_amount) }} đ
                        @endif
                    </td>
                    <td class="px-5 py-5 border-b bg-white text-sm">
                        {{ \Carbon\Carbon::parse($promo->start_date)->format('d/m/Y') }} 
                        ➝ 
                        {{ \Carbon\Carbon::parse($promo->end_date)->format('d/m/Y') }}
                    </td>
                    <td class="px-5 py-5 border-b bg-white text-sm">
                        @php
                            $today = \Carbon\Carbon::today();
                        @endphp
                        @if($today->between($promo->start_date, $promo->end_date))
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Đang chạy</span>
                        @elseif($today->gt($promo->end_date))
                            <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full">Hết hạn</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Sắp chạy</span>
                        @endif
                    </td>
                    <td class="px-5 py-5 border-b bg-white text-sm">
                        <form action="{{ route('promotions.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Xóa mã này?');">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:text-red-900 font-bold text-xs">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection