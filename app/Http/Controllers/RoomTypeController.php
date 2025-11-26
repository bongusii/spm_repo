<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Models\Hotel;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index()
    {
        // Lấy loại phòng kèm thông tin khách sạn (Eager loading)
        $roomTypes = RoomType::with('hotel')->get(); 
        return view('room_types.index', compact('roomTypes'));
    }

    public function create()
    {
        $hotels = Hotel::all(); // Lấy danh sách khách sạn để chọn
        return view('room_types.create', compact('hotels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required',
            'price_per_night' => 'required|numeric',
            'capacity' => 'required|integer|min:1',
        ]);

        // Xử lý tiện ích (amenities) - lưu dưới dạng JSON
        // Giả sử form gửi lên mảng amenities[]
        $data = $request->all();
        if ($request->has('amenities')) {
            $data['amenities'] = json_encode($request->amenities);
        }

        RoomType::create($data);

        return redirect()->route('room-types.index')->with('success', 'Đã thêm loại phòng mới.');
    }
}