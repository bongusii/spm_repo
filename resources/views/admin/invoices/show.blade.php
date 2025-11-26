<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $invoice->invoice_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS dành riêng cho máy in */
        @media print {
            .no-print { display: none !important; }
            body { background: white; -webkit-print-color-adjust: exact; }
            #invoice-area { box-shadow: none; padding: 0; margin: 0; width: 100%; }
            .border { border: 1px solid #ddd; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8 font-sans">

    <div class="max-w-3xl mx-auto mb-6 flex justify-between items-center no-print">
        <a href="{{ route('admin.bookings.index') }}" class="text-gray-600 hover:text-blue-600 font-bold flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Quay lại Quản lý
        </a>
        <button onclick="window.print()" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 font-bold flex items-center gap-2 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
            </svg>
            In Hóa Đơn / Lưu PDF
        </button>
    </div>

    <div class="max-w-3xl mx-auto bg-white p-10 rounded-lg shadow-lg" id="invoice-area">
        
        <div class="flex justify-between items-start border-b pb-8 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 uppercase tracking-wide">Hóa Đơn</h1>
                <p class="text-gray-500 mt-1">Mã số: <strong class="text-gray-800">{{ $invoice->invoice_code }}</strong></p>
                <p class="text-gray-500">Ngày xuất: {{ \Carbon\Carbon::parse($invoice->issued_at)->format('d/m/Y H:i') }}</p>
                <p class="text-gray-500">Trạng thái: 
                    <span class="font-bold text-green-600 border border-green-600 px-1 rounded text-xs uppercase">Đã thanh toán</span>
                </p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-blue-600">{{ $invoice->booking->hotel->name }}</h2>
                <p class="text-sm text-gray-600 max-w-xs ml-auto">{{ $invoice->booking->hotel->address }}</p>
                <p class="text-sm text-gray-600">Hotline: <span class="font-bold">{{ $invoice->booking->hotel->hotline }}</span></p>
            </div>
        </div>

        <div class="mb-8">
            <h3 class="text-gray-500 font-bold uppercase text-xs mb-2 tracking-wider">Khách hàng</h3>
            <div class="bg-gray-50 p-4 rounded border border-gray-200">
                <p class="font-bold text-lg text-gray-800">{{ $invoice->booking->user->name }}</p>
                <div class="flex gap-6 mt-1 text-sm text-gray-600">
                    <p>Email: {{ $invoice->booking->user->email }}</p>
                    <p>SĐT: {{ $invoice->booking->user->phone ?? '---' }}</p>
                </div>
            </div>
        </div>

        <table class="w-full mb-8 border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-600 text-left text-sm uppercase">
                    <th class="py-3 px-4 font-semibold">Diễn giải</th>
                    <th class="py-3 px-4 text-center font-semibold">Số lượng</th>
                    <th class="py-3 px-4 text-right font-semibold">Đơn giá</th>
                    <th class="py-3 px-4 text-right font-semibold">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->booking->bookingRooms as $item)
                    @php
                        // --- LOGIC FIX LỖI SỐ ÂM ---
                        $checkIn = \Carbon\Carbon::parse($invoice->booking->check_in);
                        $checkOut = \Carbon\Carbon::parse($invoice->booking->check_out);
                        
                        // Sử dụng abs() để lấy trị tuyệt đối (luôn dương)
                        $days = abs($checkOut->diffInDays($checkIn));
                        
                        // Nếu 0 đêm (sáng checkin chiều checkout) -> Tính 1
                        if ($days == 0) $days = 1;
                    @endphp

                    <tr class="border-b border-gray-100 last:border-0">
                        <td class="py-4 px-4">
                            <p class="font-bold text-gray-800">{{ $item->roomType->name }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                Check-in: {{ $checkIn->format('d/m/Y') }} <br>
                                Check-out: {{ $checkOut->format('d/m/Y') }}
                            </p>
                            @if($item->room_id)
                                <p class="text-xs text-blue-600 font-semibold mt-1">Phòng số: {{ $item->room->room_number }}</p>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center text-gray-700">
                            {{ $days }} đêm
                        </td>
                        <td class="py-4 px-4 text-right text-gray-700">
                            {{ number_format($item->price_at_booking) }} đ
                        </td>
                        <td class="py-4 px-4 text-right font-bold text-gray-800">
                            {{ number_format($item->price_at_booking * $days) }} đ
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex justify-end mb-8">
            <div class="w-1/2">
                <div class="flex justify-between mb-2 text-gray-600">
                    <span>Tổng tạm tính:</span>
                    <span class="font-medium">
                        {{ number_format($invoice->total_amount + $invoice->booking->discount_amount) }} đ
                    </span>
                </div>
                
                @if($invoice->booking->discount_amount > 0)
                <div class="flex justify-between mb-2 text-green-600 bg-green-50 px-2 py-1 rounded">
                    <span>Voucher ({{ $invoice->booking->promotion_code }}):</span>
                    <span>- {{ number_format($invoice->booking->discount_amount) }} đ</span>
                </div>
                @endif

                <div class="flex justify-between border-t border-gray-300 pt-4 mt-2 text-xl font-bold text-gray-800">
                    <span>TỔNG THANH TOÁN:</span>
                    <span>{{ number_format($invoice->total_amount) }} đ</span>
                </div>
                <p class="text-right text-xs text-gray-400 mt-2 italic">(Giá đã bao gồm thuế VAT & phí dịch vụ)</p>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-gray-200">
            <div class="grid grid-cols-2 gap-8 text-center">
                <div>
                    <p class="text-sm font-bold text-gray-600 uppercase">Khách hàng</p>
                    <p class="text-xs text-gray-400 italic mt-1">(Ký và ghi rõ họ tên)</p>
                    <div class="h-20"></div>
                    <p class="font-bold text-gray-800">_____________________</p>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-600 uppercase">Người lập phiếu</p>
                    <p class="text-xs text-gray-400 italic mt-1">(Ký và đóng dấu)</p>
                    <div class="h-20"></div>
                    <p class="font-bold text-gray-800">_____________________</p>
                </div>
            </div>
            
            <div class="text-center text-gray-500 text-sm mt-8">
                <p>Cảm ơn quý khách đã sử dụng dịch vụ tại {{ $invoice->booking->hotel->name }}!</p>
                <p class="mt-1">Mọi thắc mắc xin liên hệ hotline: <strong>{{ $invoice->booking->hotel->hotline }}</strong></p>
            </div>
        </div>
    </div>

</body>
</html>