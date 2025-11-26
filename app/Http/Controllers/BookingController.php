<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingRoom;
use App\Models\RoomType;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * 1. XEM LỊCH SỬ ĐẶT PHÒNG CỦA KHÁCH HÀNG
     */
    public function index()
    {
        // Lấy danh sách đơn của user đang đăng nhập
        $bookings = Booking::where('user_id', Auth::id())
                           ->with(['hotel', 'bookingRooms.roomType'])
                           ->latest() // Mới nhất lên đầu
                           ->get();

        return view('client.bookings.index', compact('bookings'));
    }

    /**
     * 2. XỬ LÝ ĐẶT PHÒNG (QUAN TRỌNG NHẤT)
     */
    public function store(Request $request)
    {
        // A. Validate dữ liệu đầu vào
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'room_type_id' => 'required|exists:room_types,id',
            'hotel_id' => 'required|exists:hotels,id',
        ]);

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);

        // --- BỔ SUNG: KIỂM TRA CÒN PHÒNG KHÔNG? (Check Availability) ---
        
        // 1. Đếm tổng số phòng vật lý của hạng này (Tổng cung)
        // (Trừ đi những phòng đang bảo trì)
        $totalRooms = \App\Models\Room::where('room_type_id', $request->room_type_id)
                                      ->where('status', '!=', 'maintenance')
                                      ->count();

        // 2. Đếm số phòng ĐÃ ĐƯỢC DUYỆT hoặc ĐANG Ở trong khoảng thời gian này (Tổng cầu)
        // Logic trùng lịch: (Ngày khách đến < Ngày người khác đi) VÀ (Ngày khách đi > Ngày người khác đến)
        $bookedRoomsCount = BookingRoom::where('room_type_id', $request->room_type_id)
            ->whereHas('booking', function ($query) use ($checkIn, $checkOut) {
                $query->whereIn('status', ['confirmed', 'completed']) // Chỉ tính những đơn CHẮC CHẮN (đã duyệt/đang ở)
                      ->where('check_in', '<', $checkOut)
                      ->where('check_out', '>', $checkIn);
            })
            ->sum('quantity');

        // 3. So sánh: Nếu số đã đặt >= Tổng số phòng => HẾT PHÒNG
        if ($bookedRoomsCount >= $totalRooms) {
            return redirect()->back()
                ->withInput() // Giữ lại thông tin khách vừa nhập
                ->with('error', 'Rất tiếc! Hạng phòng này đã HẾT PHÒNG trong khoảng thời gian bạn chọn. Vui lòng chọn ngày khác hoặc hạng phòng khác.');
        }
        
        // -------------------------------------------------------------

        // B. Tính toán tiền (Code cũ giữ nguyên)
        $days = abs($checkOut->diffInDays($checkIn)) ?: 1;
        $roomType = RoomType::findOrFail($request->room_type_id);
        $originalTotal = $days * $roomType->price_per_night;

        // C. Xử lý Mã giảm giá (Code cũ giữ nguyên)
        $discountAmount = 0;
        $finalTotal = $originalTotal;

        if ($request->promotion_code) {
            $promo = Promotion::where('code', $request->promotion_code)->first();
            if ($promo && $checkIn->between($promo->start_date, $promo->end_date)) {
                if ($promo->discount_percent) {
                    $discountAmount = $originalTotal * ($promo->discount_percent / 100);
                } else {
                    $discountAmount = $promo->discount_amount;
                }
                $finalTotal = $originalTotal - $discountAmount;
            }
        }
        if ($finalTotal < 0) $finalTotal = 0;

        // D. Lưu vào CSDL (Code cũ giữ nguyên)
        DB::transaction(function () use ($request, $finalTotal, $discountAmount, $roomType) {
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'hotel_id' => $request->hotel_id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'total_price' => $finalTotal,
                'promotion_code' => $request->promotion_code,
                'discount_amount' => $discountAmount,
                'status' => 'pending', 
                'notes' => $request->notes,
            ]);

            BookingRoom::create([
                'booking_id' => $booking->id,
                'room_type_id' => $roomType->id,
                'quantity' => 1,
                'price_at_booking' => $roomType->price_per_night,
            ]);
        });

        return redirect()->route('home')->with('success', 'Đặt phòng thành công! Chúng tôi sẽ sớm liên hệ.');
    }

    /**
     * 3. KHÁCH HÀNG TỰ HỦY PHÒNG
     */
    public function cancel($id)
    {
        $booking = Booking::where('id', $id)
                          ->where('user_id', Auth::id())
                          ->firstOrFail();

        // Chỉ cho hủy khi đơn còn Pending
        if ($booking->status == 'pending') {
            $booking->update(['status' => 'cancelled']);
            return redirect()->back()->with('success', 'Đã hủy đơn đặt phòng thành công.');
        }

        return redirect()->back()->with('error', 'Không thể hủy đơn hàng đã được duyệt hoặc hoàn thành.');
    }

    /**
     * 4. API KIỂM TRA MÃ GIẢM GIÁ (Cho AJAX gọi)
     */
    public function checkPromotion(Request $request)
    {
        // Tìm mã code
        $promo = Promotion::where('code', $request->code)->first();
        
        // Kiểm tra tồn tại
        if (!$promo) {
            return response()->json(['valid' => false, 'message' => 'Mã giảm giá không tồn tại!']);
        }

        // Kiểm tra ngày áp dụng (so với ngày check-in khách chọn)
        // Nếu khách chưa chọn ngày check-in, tạm thời so với ngày hôm nay
        $checkDate = $request->check_in ? Carbon::parse($request->check_in) : Carbon::today();

        if ($checkDate < $promo->start_date || $checkDate > $promo->end_date) {
            return response()->json(['valid' => false, 'message' => 'Mã này chưa đến ngày hoặc đã hết hạn!']);
        }

        // Trả về kết quả thành công
        return response()->json([
            'valid' => true,
            'message' => 'Áp dụng mã thành công!',
            'discount_percent' => $promo->discount_percent,
            'discount_amount' => $promo->discount_amount,
        ]);
    }
}