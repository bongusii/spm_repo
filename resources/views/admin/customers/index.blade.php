@extends('layouts.admin')

@section('title', 'Quản lý Khách hàng')
@section('header', 'Danh sách Thành viên (CRM)')

@section('content')
    <div class="mb-6 flex justify-between items-center bg-white p-4 rounded shadow">
        <div class="text-gray-600 font-bold">
            Tổng số khách: {{ $customers->total() }}
        </div>
        <form action="{{ route('customers.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500" 
                   placeholder="Tìm theo tên, email, sđt...">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                🔍 Tìm
            </button>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Khách hàng</th>
                    <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Liên hệ</th>
                    <th class="px-5 py-3 border-b-2 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase">Số đơn đặt</th>
                    <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Ngày tham gia</th>
                    <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-5 border-b bg-white text-sm">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                                {{ substr($customer->name, 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-gray-900 whitespace-no-wrap font-bold">{{ $customer->name }}</p>
                                <p class="text-gray-500 text-xs">ID: #{{ $customer->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-5 border-b bg-white text-sm">
                        <p class="text-gray-900"><span class="font-bold">✉️</span> {{ $customer->email }}</p>
                        <p class="text-gray-900 mt-1"><span class="font-bold">📞</span> {{ $customer->phone ?? 'Chưa cập nhật' }}</p>
                    </td>
                    <td class="px-5 py-5 border-b bg-white text-sm text-center">
                        @if($customer->bookings_count > 0)
                            <span class="bg-green-100 text-green-800 py-1 px-3 rounded-full text-xs font-bold">
                                {{ $customer->bookings_count }} đơn
                            </span>
                        @else
                            <span class="text-gray-400 text-xs">Chưa đặt</span>
                        @endif
                    </td>
                    <td class="px-5 py-5 border-b bg-white text-sm">
                        {{ $customer->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-5 py-5 border-b bg-white text-sm">
                        <a href="{{ route('customers.show', $customer->id) }}" class="text-blue-600 hover:text-blue-900 font-bold mr-3">
                            Xem lịch sử
                        </a>
                        <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Xóa khách hàng này và toàn bộ dữ liệu của họ?');">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:text-red-900">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">
            {{ $customers->appends(request()->all())->links() }}
        </div>
    </div>
@endsection