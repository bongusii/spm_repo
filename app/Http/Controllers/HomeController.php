<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Khởi tạo Query cơ bản
        $query = RoomType::with('hotel');

        // 2. Lọc theo địa điểm (Giữ nguyên logic cũ)
        if ($request->has('location') && $request->location != '') {
            $location = $request->location;
            $query->whereHas('hotel', function($q) use ($location) {
                $q->where('address', 'like', '%' . $location . '%')
                  ->orWhere('name', 'like', '%' . $location . '%');
            });
        }

        // 3. Lọc theo khoảng giá (Giữ nguyên)
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price_per_night', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        // --- 4. LOGIC MỚI: LỌC TRÙNG LỊCH (QUAN TRỌNG) ---
        if ($request->check_in && $request->check_out) {
            $checkIn = $request->check_in;
            $checkOut = $request->check_out;

            // Bước A: Đếm tổng số phòng vật lý (Tổng cung)
            // Giả sử quan hệ trong Model RoomType là: hasMany(Room::class)
            // Bạn cần đảm bảo trong Model RoomType có hàm rooms() nhé (xem Bước 2 bên dưới)
            $query->withCount(['rooms' => function($q) {
                $q->where('status', '!=', 'maintenance'); // Không tính phòng đang bảo trì
            }]);

            // Bước B: Đếm số lượng đã bị đặt trong khoảng thời gian này (Tổng cầu)
            // Logic trùng lịch: (Ngày khách đến < Ngày người khác đi) VÀ (Ngày khách đi > Ngày người khác đến)
            $query->withCount(['bookingRooms' => function($q) use ($checkIn, $checkOut) {
                $q->whereHas('booking', function($bq) use ($checkIn, $checkOut) {
                    $bq->where('status', '!=', 'cancelled') // Bỏ qua đơn đã hủy
                       ->where(function($timeQ) use ($checkIn, $checkOut) {
                           $timeQ->where('check_in', '<', $checkOut)
                                 ->where('check_out', '>', $checkIn);
                       });
                });
            }]);

            // Bước C: So sánh Cung > Cầu mới lấy
            // SQL: HAVING rooms_count > booking_rooms_count
            $query->havingRaw('rooms_count > booking_rooms_count');
        }

        // Lấy kết quả
        $featuredRooms = $query->paginate(9)->withQueryString(); // withQueryString để giữ tham số khi chuyển trang

        return view('client.home', compact('featuredRooms'));
    }

    public function show($id)
    {
        $roomType = RoomType::with('hotel')->findOrFail($id);
        return view('client.room_detail', compact('roomType'));
    }
}