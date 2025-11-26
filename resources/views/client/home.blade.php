@extends('layouts.client')

@section('title', 'Trang chủ')

@section('content')
    <div class="relative bg-cover bg-center h-[500px]" style="background-image: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        
        <div class="relative container mx-auto flex flex-col justify-center h-full text-white px-6">
            <h1 class="text-5xl font-bold mb-4">Tìm kỳ nghỉ tuyệt vời của bạn</h1>
            <p class="text-xl mb-8">Trải nghiệm dịch vụ đẳng cấp tại chuỗi khách sạn hàng đầu.</p>

            <div class="bg-white p-6 rounded-lg shadow-lg text-gray-800 max-w-4xl">
                <form action="{{ route('home') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
    
                    <div class="col-span-1">
                        <label class="block font-bold text-sm mb-1">Địa điểm / Khách sạn</label>
                        <input type="text" name="location" 
                            value="{{ request('location') }}"  {{-- Giữ lại giá trị cũ --}}
                            class="w-full border rounded px-3 py-2 text-gray-800" 
                            placeholder="Nhập tên TP hoặc Khách sạn">
                    </div>

                    <div>
                        <label class="block font-bold text-sm mb-1">Ngày nhận phòng</label>
                        <input type="date" name="check_in" 
                            value="{{ request('check_in') }}" {{-- Giữ lại giá trị cũ --}}
                            class="w-full border rounded px-3 py-2 text-gray-800">
                    </div>

                    <div>
                        <label class="block font-bold text-sm mb-1">Ngày trả phòng</label>
                        <input type="date" name="check_out" 
                            value="{{ request('check_out') }}" {{-- Giữ lại giá trị cũ --}}
                            class="w-full border rounded px-3 py-2 text-gray-800">
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700 transition shadow-lg">
                            🔍 Tìm Kiếm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Các Hạng Phòng Nổi Bật</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredRooms as $room)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                <img class="w-full h-48 object-cover" src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=800&q=80" alt="Room Image">
                
                <div class="p-6">
                    <div class="flex justify-between items-baseline">
                        <span class="text-xs font-bold text-blue-600 uppercase">{{ $room->hotel->name }}</span>
                        <span class="text-sm text-gray-500">{{ $room->capacity }} người</span>
                    </div>
                    
                    <h3 class="mt-2 text-xl font-bold text-gray-900 leading-tight">
                        {{ $room->name }}
                    </h3>
                    
                    <div class="mt-2">
                        <span class="text-2xl font-bold text-gray-900">{{ number_format($room->price_per_night) }} đ</span>
                        <span class="text-gray-600 text-sm">/ đêm</span>
                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">
                        {{-- Hiển thị tiện ích --}}
                        @if($room->amenities)
                            @foreach(json_decode($room->amenities) as $amenity)
                                <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">{{ $amenity }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('room.show', $room->id) }}" class="block w-full text-center bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700">
                            Đặt Phòng Ngay
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $featuredRooms->links() }}
        </div>
    </div>
@endsection