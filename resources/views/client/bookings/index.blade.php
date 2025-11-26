@extends('layouts.client')

@section('title', 'Lịch sử đặt phòng')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Lịch sử đặt phòng của bạn</h1>

    @if($bookings->isEmpty())
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <p class="text-gray-500 mb-4">Bạn chưa có đơn đặt phòng nào.</p>
            <a href="{{ route('home') }}" class="text-blue-600 font-bold hover:underline">Đặt phòng ngay</a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($bookings as $booking)
                <div class="bg-white p-6 rounded-lg shadow border-l-4 {{ $booking->status == 'pending' ? 'border-yellow-400' : ($booking->status == 'confirmed' ? 'border-green-500' : 'border-gray-300') }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-xl font-bold text-blue-800">{{ $booking->hotel->name }}</h2>
                            <p class="text-sm text-gray-500">Mã đơn: #{{ $booking->id }} | Ngày đặt: {{ $booking->created_at->format('d/m/Y') }}</p>
                            
                            <div class="mt-2 text-gray-700">
                                @foreach($booking->bookingRooms as $detail)
                                    <div>• {{ $detail->roomType->name }} (x{{ $detail->quantity }})</div>
                                @endforeach
                            </div>

                            <div class="mt-2 text-sm">
                                <span class="font-semibold">Check-in:</span> {{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }} 
                                <span class="mx-2">|</span> 
                                <span class="font-semibold">Check-out:</span> {{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="text-2xl font-bold text-red-600 mb-2">{{ number_format($booking->total_price) }} đ</p>
                            
                            @if($booking->status == 'pending')
                                <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full font-semibold uppercase tracking-wide">Chờ duyệt</span>
                                
                                <form action="{{ route('my-bookings.cancel', $booking->id) }}" method="POST" class="mt-3">
                                    @csrf 
                                    <button onclick="return confirm('Bạn chắc chắn muốn hủy?')" class="text-red-500 hover:text-red-700 text-sm underline">
                                        Hủy đơn này
                                    </button>
                                </form>

                            @elseif($booking->status == 'confirmed')
                                <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-semibold uppercase tracking-wide">Đã xác nhận</span>
                                <p class="text-xs text-green-600 mt-1">Vui lòng đến đúng giờ!</p>
                            @elseif($booking->status == 'cancelled')
                                <span class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full font-semibold uppercase tracking-wide">Đã hủy</span>
                            @elseif($booking->status == 'completed')
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-semibold uppercase tracking-wide">Hoàn thành</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection