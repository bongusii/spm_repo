<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelController extends Controller
{
    /**
     * Hiển thị danh sách khách sạn
     */
    public function index()
    {
        $user = Auth::user();

        // Nếu là Branch Manager -> Chỉ thấy khách sạn mình quản lý
        if ($user->role === 'branch_manager') {
            $hotels = Hotel::where('manager_id', $user->id)->get();
        } else {
            // Super Admin thấy hết
            $hotels = Hotel::all();
        }

        return view('hotels.index', compact('hotels'));
    }

    /**
     * Form thêm mới (Chỉ Super Admin)
     */
    public function create()
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Chỉ Quản trị viên mới được quyền mở thêm Chi nhánh mới!');
        }

        // Lấy danh sách các quản lý để gán (nếu cần)
        $managers = User::whereIn('role', ['branch_manager', 'super_admin'])->get();

        return view('hotels.create', compact('managers'));
    }

    /**
     * Lưu mới (Chỉ Super Admin)
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }

        $request->validate([
            'name' => 'required|max:255',
            'address' => 'required',
            'hotline' => 'required',
            'manager_id' => 'nullable|exists:users,id'
        ]);

        Hotel::create($request->all());

        return redirect()->route('hotels.index')->with('success', 'Đã thêm chi nhánh khách sạn mới thành công.');
    }

    /**
     * Form chỉnh sửa
     */
    public function edit($id)
    {
        $hotel = Hotel::findOrFail($id);
        $user = Auth::user();

        // Phân quyền: Branch Manager chỉ được sửa khách sạn của mình
        if ($user->role === 'branch_manager' && $hotel->manager_id !== $user->id) {
            abort(403, 'Bạn chỉ được sửa thông tin khách sạn do mình quản lý!');
        }

        // Lấy danh sách quản lý (chỉ để Super Admin chọn lại người quản lý khác nếu muốn)
        $managers = User::whereIn('role', ['branch_manager', 'super_admin'])->get();

        return view('hotels.edit', compact('hotel', 'managers'));
    }

    /**
     * Cập nhật dữ liệu
     */
    public function update(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);
        $user = Auth::user();

        // Phân quyền lại lần nữa cho chắc
        if ($user->role === 'branch_manager' && $hotel->manager_id !== $user->id) {
            abort(403, 'Bạn không có quyền sửa khách sạn này.');
        }

        $request->validate([
            'name' => 'required|max:255',
            'address' => 'required',
            'hotline' => 'required',
        ]);

        // Branch Manager không được phép tự đổi người quản lý (manager_id) của chính mình
        // Ta chỉ lấy manager_id từ request nếu user là Super Admin
        $data = $request->all();
        
        if ($user->role !== 'super_admin') {
            unset($data['manager_id']); // Loại bỏ field này nếu không phải Super Admin
        }

        $hotel->update($data);

        return redirect()->route('hotels.index')->with('success', 'Cập nhật thông tin khách sạn thành công.');
    }

    /**
     * Xóa khách sạn (Chỉ Super Admin)
     */
    public function destroy($id)
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Chỉ Quản trị viên mới được xóa chi nhánh!');
        }

        $hotel = Hotel::findOrFail($id);
        $hotel->delete();

        return redirect()->route('hotels.index')->with('success', 'Đã xóa chi nhánh khách sạn.');
    }
}