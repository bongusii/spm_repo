<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    // Danh sách đơn đặt phòng
    public function index()
    {
        $user = auth()->user();
        $query = Booking::with(['user', 'hotel', 'bookingRooms.roomType']);

        // --- LOGIC PHÂN QUYỀN ---
        if ($user->role === 'branch_manager') {
            $myHotel = $user->managedHotel;
            if($myHotel) {
                $query->where('hotel_id', $myHotel->id);
            } else {
                // Nếu chưa gán khách sạn thì không thấy gì
                $query->where('id', 0); 
            }
        }
        // ------------------------

        $bookings = $query->latest()->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    // Cập nhật trạng thái (Duyệt / Hủy)
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::with('bookingRooms')->findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:confirmed,cancelled,completed'
        ]);

        // LOGIC 1: KHI BẤM DUYỆT (CONFIRMED) -> TỰ ĐỘNG XẾP PHÒNG
        if ($request->status == 'confirmed' && $booking->status == 'pending') {
            foreach ($booking->bookingRooms as $detail) {
                // Tìm 1 phòng trống thuộc loại phòng khách đặt
                $freeRoom = Room::where('room_type_id', $detail->room_type_id)
                                ->where('status', 'available') // Phải đang trống
                                ->first();

                if (!$freeRoom) {
                    return redirect()->back()->with('error', 'Hết phòng trống cho loại phòng này! Không thể duyệt.');
                }

                // Gán phòng này vào đơn hàng
                $detail->update(['room_id' => $freeRoom->id]);

                // Đổi trạng thái phòng sang "booked"
                $freeRoom->update(['status' => 'booked']);
            }
        }

        // LOGIC 2: KHI BẤM TRẢ PHÒNG (COMPLETED) HOẶC HỦY (CANCELLED) -> TRẢ LẠI PHÒNG TRỐNG
        if (($request->status == 'completed' || $request->status == 'cancelled') && $booking->status == 'confirmed') {
            foreach ($booking->bookingRooms as $detail) {
                if ($detail->room_id) {
                    // Tìm phòng đã gán và trả về trạng thái 'available'
                    Room::where('id', $detail->room_id)->update(['status' => 'available']);
                }
            }
        }

        // Cập nhật trạng thái đơn hàng
        $booking->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái và điều phối phòng thành công!');
    }
}