@extends('layouts.admin')

@section('title', 'Tạo Khuyến mãi')
@section('header', 'Tạo Mã Giảm Giá Mới')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow">
    <form action="{{ route('promotions.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Mã Code (Viết hoa)</label>
            <input type="text" name="code" class="w-full border rounded px-3 py-2 uppercase font-bold text-blue-600" placeholder="VD: SUMMER2025" required>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Giảm theo %</label>
                <input type="number" name="discount_percent" class="w-full border rounded px-3 py-2" placeholder="VD: 10 (để trống nếu giảm tiền)">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Hoặc Giảm số tiền (VNĐ)</label>
                <input type="number" name="discount_amount" class="w-full border rounded px-3 py-2" placeholder="VD: 50000">
            </div>
        </div>
        <p class="text-xs text-gray-500 mb-6 italic">* Chỉ nhập 1 trong 2 ô trên. Ưu tiên % nếu nhập cả hai.</p>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Ngày bắt đầu</label>
                <input type="date" name="start_date" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Ngày kết thúc</label>
                <input type="date" name="end_date" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700">
            Lưu Mã Khuyến Mãi
        </button>
    </form>
</div>
@endsection