<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomTypeController extends Controller
{
    /**
     * 1. DANH SÁCH: Chỉ hiện loại phòng thuộc khách sạn mình quản lý
     */
    public function index()
    {
        $user = Auth::user();
        $query = RoomType::with('hotel');

        if ($user->role === 'branch_manager') {
            // Lọc: Chỉ lấy RoomType mà Hotel của nó có manager_id là mình
            $query->whereHas('hotel', function($q) use ($user) {
                $q->where('manager_id', $user->id);
            });
        }

        $roomTypes = $query->get();
        return view('room_types.index', compact('roomTypes'));
    }

    /**
     * 2. FORM TẠO MỚI: Chỉ được chọn khách sạn mình quản lý
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'branch_manager') {
            // Chỉ lấy danh sách khách sạn do user này quản lý
            $hotels = Hotel::where('manager_id', $user->id)->get();
            
            // Nếu chưa được gán khách sạn nào -> Báo lỗi
            if ($hotels->isEmpty()) {
                return redirect()->route('room-types.index')->with('error', 'Bạn chưa được gán quản lý khách sạn nào!');
            }
        } else {
            // Super Admin thấy hết
            $hotels = Hotel::all();
        }

        return view('room_types.create', compact('hotels'));
    }

    /**
     * 3. LƯU MỚI: Chặn nếu cố tình hack ID khách sạn khác
     */
    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        // KIỂM TRA QUYỀN: Nếu là Branch Manager
        if ($user->role === 'branch_manager') {
            $targetHotel = Hotel::findOrFail($request->hotel_id);
            if ($targetHotel->manager_id !== $user->id) {
                abort(403, 'Bạn không thể tạo phòng cho khách sạn của người khác!');
            }
        }

        $data = $request->all();
        if ($request->has('amenities')) {
            $data['amenities'] = json_encode($request->amenities);
        }

        RoomType::create($data);

        return redirect()->route('room-types.index')->with('success', 'Thêm loại phòng thành công!');
    }

    /**
     * 4. FORM SỬA: Chặn xem phòng người khác
     */
    public function edit($id)
    {
        $roomType = RoomType::findOrFail($id);
        $user = Auth::user();

        // KIỂM TRA: Loại phòng này có thuộc khách sạn do mình quản lý không?
        if ($user->role === 'branch_manager') {
            if ($roomType->hotel->manager_id !== $user->id) {
                abort(403, 'Bạn không có quyền chỉnh sửa loại phòng này!');
            }
            // Khi sửa, danh sách Hotel cũng chỉ hiện Hotel của mình
            $hotels = Hotel::where('manager_id', $user->id)->get();
        } else {
            $hotels = Hotel::all();
        }

        return view('room_types.edit', compact('roomType', 'hotels'));
    }

    /**
     * 5. CẬP NHẬT: Chặn sửa phòng người khác
     */
    public function update(Request $request, $id)
    {
        $roomType = RoomType::findOrFail($id);
        $user = Auth::user();

        // KIỂM TRA QUYỀN
        if ($user->role === 'branch_manager') {
            if ($roomType->hotel->manager_id !== $user->id) {
                abort(403, 'Bạn không có quyền cập nhật loại phòng này!');
            }
            
            // Chặn luôn việc cố tình đổi hotel_id sang khách sạn khác
            $targetHotel = Hotel::find($request->hotel_id);
            if ($targetHotel && $targetHotel->manager_id !== $user->id) {
                abort(403, 'Bạn không thể chuyển phòng này sang khách sạn khác!');
            }
        }

        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
        ]);

        $data = $request->all();
        if ($request->has('amenities')) {
            $data['amenities'] = json_encode($request->amenities);
        } else {
            // Nếu bỏ tick hết thì set null hoặc mảng rỗng
            $data['amenities'] = json_encode([]); 
        }

        $roomType->update($data);

        return redirect()->route('room-types.index')->with('success', 'Cập nhật loại phòng thành công!');
    }

    /**
     * 6. XÓA: Chặn xóa phòng người khác
     */
    public function destroy($id)
    {
        $roomType = RoomType::findOrFail($id);
        $user = Auth::user();

        // KIỂM TRA QUYỀN
        if ($user->role === 'branch_manager') {
            if ($roomType->hotel->manager_id !== $user->id) {
                abort(403, 'Bạn không có quyền xóa loại phòng này!');
            }
        }

        $roomType->delete();

        return redirect()->route('room-types.index')->with('success', 'Đã xóa loại phòng.');
    }
}