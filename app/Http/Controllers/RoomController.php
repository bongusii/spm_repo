<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Hiển thị danh sách phòng (Grid & List View)
     */
    public function index()
    {
        $user = auth()->user();
        $query = Room::with('roomType.hotel');

        if ($user->role === 'branch_manager') {
            $myHotel = $user->managedHotel;
            // Chỉ lấy phòng thuộc khách sạn của mình
            $query->whereHas('roomType', function($q) use ($myHotel) {
                $q->where('hotel_id', $myHotel->id ?? 0);
            });
        }

        $rooms = $query->get();
        return view('rooms.index', compact('rooms'));
    }

    /**
     * Hiển thị form thêm mới
     */
    public function create()
    {
        $user = auth()->user();
        $query = RoomType::with('hotel');

        if ($user->role === 'branch_manager') {
            $myHotel = $user->managedHotel;
            $query->where('hotel_id', $myHotel->id ?? 0);
        }

        $roomTypes = $query->get();
        return view('rooms.create', compact('roomTypes'));
    }

    /**
     * Lưu phòng mới vào CSDL
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => 'required',
            'status' => 'required'
        ]);

        Room::create($request->all());

        return redirect()->route('rooms.index')->with('success', 'Đã thêm phòng mới thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa (Nếu bạn cần dùng sau này)
     */
    public function edit($id)
    {
        $room = Room::findOrFail($id);
        $roomTypes = RoomType::with('hotel')->get();
        return view('rooms.edit', compact('room', 'roomTypes'));
    }

    /**
     * Cập nhật thông tin phòng
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'room_number' => 'required',
            'status' => 'required'
        ]);

        $room = Room::findOrFail($id);
        $room->update($request->all());

        return redirect()->route('rooms.index')->with('success', 'Cập nhật phòng thành công!');
    }

    /**
     * Xóa phòng
     */
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Đã xóa phòng thành công!');
    }
}