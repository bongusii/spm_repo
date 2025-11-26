@extends('layouts.admin')

@section('title', 'Quản lý Phòng')
@section('header', 'Sơ đồ & Danh sách Phòng')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý Phòng</h2>
            <p class="text-gray-600 text-sm">Tổng số phòng: {{ $rooms->count() }}</p>
        </div>
        <a href="{{ route('rooms.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Thêm Phòng Mới
        </a>
    </div>

    <div class="mb-4 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" role="tablist">
            <li class="mr-2" role="presentation">
                <button onclick="switchTab('grid-view')" id="grid-tab" class="tab-btn inline-block p-4 border-b-2 rounded-t-lg active hover:text-blue-600 hover:border-blue-300 border-blue-600 text-blue-600" type="button">
                    🏨 Sơ đồ phòng (Grid)
                </button>
            </li>
            <li class="mr-2" role="presentation">
                <button onclick="switchTab('list-view')" id="list-tab" class="tab-btn inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 text-gray-500" type="button">
                    📄 Danh sách chi tiết (List)
                </button>
            </li>
        </ul>
    </div>

    <div id="grid-view" class="tab-content block">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($rooms as $room)
                @php
                    // Xác định màu sắc dựa trên trạng thái
                    $statusColor = 'bg-green-100 border-green-500 text-green-800'; // Trống
                    $icon = '🚪';
                    $statusText = 'Sẵn sàng';
                    
                    if($room->status == 'booked') {
                        $statusColor = 'bg-red-100 border-red-500 text-red-800'; // Đã đặt
                        $icon = '👤';
                        $statusText = 'Đang ở';
                    } elseif($room->status == 'maintenance') {
                        $statusColor = 'bg-gray-200 border-gray-500 text-gray-800'; // Bảo trì
                        $icon = '🔧';
                        $statusText = 'Bảo trì';
                    }
                @endphp

                <div class="relative border-l-4 rounded shadow-sm p-4 hover:shadow-md transition duration-200 bg-white {{ $statusColor }}">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-2xl font-bold">{{ $room->room_number }}</span>
                        <span class="text-xl">{{ $icon }}</span>
                    </div>
                    
                    <div class="text-xs font-semibold uppercase tracking-wide opacity-75 mb-1">
                        {{ $room->roomType->name }}
                    </div>
                    
                    <div class="text-xs mt-2 font-bold bg-white bg-opacity-50 px-2 py-1 rounded inline-block">
                        {{ $statusText }}
                    </div>

                    <a href="{{ route('rooms.edit', $room->id) }}" class="absolute top-0 right-0 p-1 opacity-0 hover:opacity-100 transition">
                        ✏️
                    </a>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6 flex gap-4 text-sm text-gray-600">
            <div class="flex items-center"><span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span> Trống</div>
            <div class="flex items-center"><span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span> Có khách</div>
            <div class="flex items-center"><span class="w-3 h-3 bg-gray-500 rounded-full mr-2"></span> Bảo trì</div>
        </div>
    </div>

    <div id="list-view" class="tab-content hidden">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Số Phòng</th>
                        <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Loại & Khách Sạn</th>
                        <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Giá niêm yết</th>
                        <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Trạng Thái</th>
                        <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-5 border-b bg-white text-sm font-bold text-xl text-blue-800">
                            {{ $room->room_number }}
                        </td>
                        <td class="px-5 py-5 border-b bg-white text-sm">
                            <div class="font-bold">{{ $room->roomType->name }}</div>
                            <div class="text-gray-500 text-xs">{{ $room->roomType->hotel->name }}</div>
                        </td>
                        <td class="px-5 py-5 border-b bg-white text-sm font-bold text-gray-600">
                            {{ number_format($room->roomType->price_per_night) }} đ
                        </td>
                        <td class="px-5 py-5 border-b bg-white text-sm">
                            @if($room->status == 'available')
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">Sẵn sàng</span>
                            @elseif($room->status == 'booked')
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded-full">Đã đặt</span>
                            @else
                                <span class="bg-gray-200 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">Bảo trì</span>
                            @endif
                        </td>
                        <td class="px-5 py-5 border-b bg-white text-sm">
                            <a href="{{ route('rooms.edit', $room->id) }}" class="text-blue-600 hover:text-blue-900 font-bold text-xs">Sửa</a>
                            <span class="mx-1">|</span>
                            <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Xóa phòng này?');">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:text-red-900 font-bold text-xs">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            // 1. Ẩn hết nội dung tab
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('block'));

            // 2. Hiện tab được chọn
            document.getElementById(tabId).classList.remove('hidden');
            document.getElementById(tabId).classList.add('block');

            // 3. Reset style các nút
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('text-blue-600', 'border-blue-600', 'border-b-2');
                btn.classList.add('text-gray-500', 'border-transparent');
            });

            // 4. Active nút được chọn
            let activeBtnId = (tabId === 'grid-view') ? 'grid-tab' : 'list-tab';
            let activeBtn = document.getElementById(activeBtnId);
            activeBtn.classList.remove('text-gray-500', 'border-transparent');
            activeBtn.classList.add('text-blue-600', 'border-blue-600', 'border-b-2');
        }
    </script>
@endsection