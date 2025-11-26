@extends('layouts.admin')

@section('title', 'Quản lý Đặt phòng')
@section('header', 'Danh sách Đơn đặt phòng')

@section('content')
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        
        <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <span class="text-gray-600 font-medium">Tổng số đơn: {{ $bookings->total() }}</span>
            </div>

        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mã Đơn</th>
                    <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Khách hàng</th>
                    <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Chi tiết phòng</th>
                    <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Thanh toán</th>
                    <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="font-bold text-gray-700">#{{ $booking->id }}</span> <br>
                        <span class="text-xs text-gray-500">{{ $booking->created_at->format('d/m/Y H:i') }}</span>
                    </td>

                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <div class="flex items-center">
                            <div class="ml-3">
                                <p class="text-gray-900 whitespace-no-wrap font-bold">
                                    {{ $booking->user->name ?? 'Khách vãng lai' }}
                                </p>
                                <p class="text-gray-600 text-xs">{{ $booking->user->email ?? '' }}</p>
                                <p class="text-blue-600 text-xs font-semibold">{{ $booking->user->phone ?? '---' }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <div class="text-gray-900 font-bold mb-1">{{ $booking->hotel->name }}</div>
                        
                        @foreach($booking->bookingRooms as $detail)
                            <div class="text-xs mt-1 border-l-2 border-blue-400 pl-2">
                                <span class="text-gray-700 font-medium">{{ $detail->roomType->name }}</span> 
                                <span class="text-gray-500">(x{{ $detail->quantity }})</span>
                                
                                @if($detail->room_id)
                                    <div class="text-blue-600 font-bold">
                                        → Phòng: {{ $detail->room->room_number ?? 'Đã xóa' }}
                                    </div>
                                @else
                                    <div class="text-gray-400 italic">→ Chưa xếp phòng</div>
                                @endif
                            </div>
                        @endforeach

                        <div class="text-xs text-gray-500 mt-2 bg-gray-100 p-1 rounded inline-block">
                            📅 {{ \Carbon\Carbon::parse($booking->check_in)->format('d/m') }} 
                            ➝ {{ \Carbon\Carbon::parse($booking->check_out)->format('d/m') }}
                        </div>
                    </td>

                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="font-bold text-red-600 text-lg">
                            {{ number_format($booking->total_price) }} đ
                        </p>
                        
                        @if($booking->discount_amount > 0)
                            <div class="text-xs text-green-600 mt-1">
                                <span class="font-bold">Voucher:</span> {{ $booking->promotion_code }} <br>
                                (Giảm: -{{ number_format($booking->discount_amount) }} đ)
                            </div>
                        @endif
                    </td>

                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        @if($booking->status == 'pending')
                            <span class="relative inline-block px-3 py-1 font-semibold text-yellow-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-yellow-200 opacity-50 rounded-full"></span>
                                <span class="relative">⏳ Chờ duyệt</span>
                            </span>
                        @elseif($booking->status == 'confirmed')
                            <span class="relative inline-block px-3 py-1 font-semibold text-blue-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-blue-200 opacity-50 rounded-full"></span>
                                <span class="relative">✓ Đã duyệt</span>
                            </span>
                        @elseif($booking->status == 'cancelled')
                            <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                <span class="relative">✕ Đã hủy</span>
                            </span>
                        @else
                            <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                <span class="relative">★ Hoàn thành</span>
                            </span>
                        @endif
                    </td>

                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm w-40">
                        <div class="flex flex-col gap-2">
                            
                            @if($booking->status == 'pending')
                                <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button onclick="return confirm('Duyệt đơn và tự động xếp phòng?')" 
                                            class="w-full bg-green-100 hover:bg-green-200 text-green-800 text-xs font-bold py-1 px-2 rounded border border-green-300 transition">
                                        ✓ Duyệt đơn
                                    </button>
                                </form>
                            @endif

                            @if($booking->status == 'confirmed')
                                <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button onclick="return confirm('Khách đã trả phòng và thanh toán?')" 
                                            class="w-full bg-blue-100 hover:bg-blue-200 text-blue-800 text-xs font-bold py-1 px-2 rounded border border-blue-300 transition">
                                        ⤾ Checkout
                                    </button>
                                </form>
                            @endif

                            @if($booking->status == 'confirmed' || $booking->status == 'completed')
                                <a href="{{ route('invoices.generate', $booking->id) }}" target="_blank" 
                                   class="w-full text-center bg-gray-800 hover:bg-black text-white text-xs font-bold py-1 px-2 rounded transition shadow">
                                    🖨️ Xuất Hóa Đơn
                                </a>
                            @endif

                            @if($booking->status != 'cancelled' && $booking->status != 'completed')
                                <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST" class="mt-1">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button onclick="return confirm('Hủy đơn hàng này? Phòng đã xếp (nếu có) sẽ được trả lại.')" 
                                            class="w-full text-red-500 hover:text-red-700 text-xs underline">
                                        Hủy đơn
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                
                @if($bookings->isEmpty())
                <tr>
                    <td colspan="6" class="px-5 py-5 text-center text-gray-500 italic">
                        Chưa có đơn đặt phòng nào.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>

        <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
            {{ $bookings->links() }}
        </div>
    </div>
@endsection