<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    // 1. Danh sách khách hàng (Có tìm kiếm)
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');

        // Tìm kiếm theo tên hoặc email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Lấy danh sách + Đếm số đơn hàng đã đặt
        $customers = $query->withCount('bookings')
                           ->latest()
                           ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    // 2. Xem chi tiết & Lịch sử đặt phòng của khách
    public function show($id)
    {
        $customer = User::where('role', 'customer')
                        ->with(['bookings' => function($q) {
                            $q->latest(); // Lấy đơn mới nhất lên đầu
                        }, 'bookings.hotel', 'bookings.bookingRooms.roomType'])
                        ->findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }

    // 3. Xóa khách hàng
    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        
        // (Tùy chọn) Kiểm tra nếu còn đơn hàng chưa hoàn thành thì không cho xóa
        // if($customer->bookings()->where('status', 'pending')->exists()) { ... }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Đã xóa khách hàng thành công!');
    }
}