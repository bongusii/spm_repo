<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()->get();
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:promotions,code|uppercase',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Kiểm tra: Phải nhập ít nhất 1 loại giảm giá (% hoặc tiền)
        if (!$request->discount_percent && !$request->discount_amount) {
            return back()->withErrors(['error' => 'Vui lòng nhập mức giảm (% hoặc số tiền).']);
        }

        Promotion::create($request->all());

        return redirect()->route('promotions.index')->with('success', 'Tạo mã giảm giá thành công!');
    }

    public function destroy($id)
    {
        Promotion::findOrFail($id)->delete();
        return redirect()->route('promotions.index')->with('success', 'Đã xóa mã khuyến mãi.');
    }
}