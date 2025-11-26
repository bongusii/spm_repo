@extends('layouts.admin')

@section('title', 'Chi tiết khách hàng')
@section('header', 'Hồ sơ: ' . $customer->name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('customers.index') }}" class="text-gray-600 hover:text-blue-600">
            ← Quay lại danh sách
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow h-fit">
            <div class="flex flex-col items-center">
                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-4xl font-bold mb-4">
                    {{ substr($customer->name, 0, 1) }}
                </div>
                <h3 class="text-xl font-bold">{{ $customer->name }}</h3>
                <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded mt-2">Thành viên</span>
            </div>
            
            <hr class="my-4">
            
            <div class="space-y-3">
                <p><strong>Email:</strong> {{ $customer->email }}</p>
                <p><strong>Điện thoại:</strong> {{ $customer->phone ?? '---' }}</p>
                <p><strong>Ngày tham gia:</strong> {{ $customer->created_at->format('d/m/Y') }}</p>
                <p><strong>Tổng chi tiêu:</strong> 
                    <span class="text-red-600 font-bold">
                        {{ number_format($customer->bookings->where('status', '!=', 'cancelled')->sum('total_price')) }} đ
                    </span>
                </p>
            </div>
        </div>

        <div class="md:col-span-2 bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold mb-4 border-b pb-2">Lịch sử giao dịch</h3>
            
            @if($customer->bookings->isEmpty())
                <p class="text-gray-500 italic">Khách hàng này chưa có giao dịch nào.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-4 py-3">Mã đơn</th>
                                <th class="px-4 py-3">Khách sạn</th>
                                <th class="px-4 py-3">Ngày ở</th>
                                <th class="px-4 py-3">Tổng tiền</th>
                                <th class="px-4 py-3">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->bookings as $booking)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-4 py-3">#{{ $booking->id }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $booking->hotel->name }}</td>
                                <td class="px-4 py-3">
                                    {{ \Carbon\Carbon::parse($booking->check_in)->format('d/m') }} - 
                                    {{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 font-bold">{{ number_format($booking->total_price) }} đ</td>
                                <td class="px-4 py-3">
                                    @if($booking->status == 'confirmed')
                                        <span class="text-green-600 font-bold">Đã duyệt</span>
                                    @elseif($booking->status == 'pending')
                                        <span class="text-yellow-600 font-bold">Chờ duyệt</span>
                                    @elseif($booking->status == 'cancelled')
                                        <span class="text-red-600">Đã hủy</span>
                                    @else
                                        <span class="text-blue-600">Hoàn thành</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection