<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    // Danh sách đơn đặt phòng
    public function index()
    {
        // Lấy đơn mới nhất lên đầu, nạp sẵn thông tin user và hotel để query nhanh
        $bookings = Booking::with(['user', 'hotel', 'bookingRooms.roomType'])
                           ->latest()
                           ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    // Cập nhật trạng thái (Duyệt / Hủy)
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        // Validate trạng thái gửi lên
        $request->validate([
            'status' => 'required|in:confirmed,cancelled,completed'
        ]);

        $booking->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}