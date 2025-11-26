<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Invoice;
use Illuminate\Http\Request;

class AdminInvoiceController extends Controller
{
    // Tạo hoặc Xem hóa đơn
    public function generate($bookingId)
    {
        $booking = Booking::with(['user', 'hotel', 'bookingRooms.roomType'])->findOrFail($bookingId);

        // Kiểm tra xem đơn này đã tạo hóa đơn chưa?
        $invoice = Invoice::where('booking_id', $bookingId)->first();

        if (!$invoice) {
            // Nếu chưa có thì tạo mới
            $invoice = Invoice::create([
                'booking_id' => $booking->id,
                // Tạo mã hóa đơn: INV + Năm + ID đơn (VD: INV-2025-0012)
                'invoice_code' => 'INV-' . date('Y') . '-' . str_pad($booking->id, 4, '0', STR_PAD_LEFT),
                'issued_at' => now(),
                'total_amount' => $booking->total_price,
                'payment_method' => 'cash', // Mặc định tiền mặt, có thể làm form chọn sau
                'status' => 'paid'
            ]);
            
            // Cập nhật trạng thái đơn hàng thành Completed luôn nếu đang là Confirmed
            if($booking->status == 'confirmed') {
                $booking->update(['status' => 'completed']);
                
                // Trả phòng vật lý về trạng thái Available (Logic cũ của bạn)
                foreach ($booking->bookingRooms as $detail) {
                    if ($detail->room_id) {
                         \App\Models\Room::where('id', $detail->room_id)->update(['status' => 'available']);
                    }
                }
            }
        }

        return redirect()->route('invoices.show', $invoice->id);
    }

    // Hiển thị trang hóa đơn (để in)
    public function show($id)
    {
        $invoice = Invoice::with(['booking.user', 'booking.hotel', 'booking.bookingRooms.roomType'])->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }
}