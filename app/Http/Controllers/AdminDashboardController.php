<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Để dùng hàm tính toán SQL

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // 1. Khởi tạo Query
        $bookingsQuery = \App\Models\Booking::query();
        $roomsQuery = \App\Models\Room::query();

        // 2. Nếu là Branch Manager -> Chỉ lọc theo Hotel của họ
        if ($user->role === 'branch_manager') {
            // Lấy khách sạn do user này quản lý
            $myHotel = $user->managedHotel;

            if (!$myHotel) {
                return abort(403, 'Tài khoản quản lý này chưa được gán cho khách sạn nào!');
            }

            // Lọc Booking theo hotel_id
            $bookingsQuery->where('hotel_id', $myHotel->id);
            
            // Lọc Room thông qua bảng room_types
            // (Vì bảng rooms không có hotel_id trực tiếp, nó nối qua room_types)
            $roomsQuery->whereHas('roomType', function($q) use ($myHotel) {
                $q->where('hotel_id', $myHotel->id);
            });
        }

        // 3. Tính toán số liệu (Dựa trên Query đã lọc ở trên)
        $totalRevenue = $bookingsQuery->clone()->whereIn('status', ['confirmed', 'completed'])->sum('total_price');
        $newBookings = $bookingsQuery->clone()->where('status', 'pending')->count();
        
        // Khách hàng thì đếm chung toàn hệ thống (hoặc lọc user từng đặt tại hotel này nếu muốn chặt chẽ)
        $totalCustomers = \App\Models\User::where('role', 'customer')->count();
        
        $totalRooms = $roomsQuery->count();

        // 4. Biểu đồ doanh thu (Cũng phải lọc)
        $monthlyStats = $bookingsQuery->clone()
            ->select(
                \Illuminate\Support\Facades\DB::raw('MONTH(check_in) as month'), 
                \Illuminate\Support\Facades\DB::raw('SUM(total_price) as total')
            )
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereYear('check_in', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyStats[$i] ?? 0;
        }

        return view('admin.dashboard', compact(
            'totalRevenue', 'newBookings', 'totalCustomers', 'totalRooms', 'chartData'
        ));
    }
}
