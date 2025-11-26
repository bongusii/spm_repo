@extends('layouts.client')

@section('title', $roomType->name)

@section('content')
<div class="container mx-auto px-6 py-8">
    
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow" role="alert">
            <p class="font-bold flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Lỗi đặt phòng:
            </p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow" role="alert">
            <p class="font-bold flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Thành công:
            </p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <div>
            <div class="relative overflow-hidden rounded-lg shadow-lg mb-6 group">
                <img class="w-full h-96 object-cover transform group-hover:scale-105 transition duration-500" 
                     src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=800&q=80" 
                     alt="{{ $roomType->name }}">
                <div class="absolute top-4 right-4 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                    {{ $roomType->hotel->name }}
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow border border-gray-100">
                <h2 class="text-xl font-bold mb-4 text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    Tiện ích phòng
                </h2>
                <div class="flex gap-2 flex-wrap">
                    @if($roomType->amenities)
                        @foreach(json_decode($roomType->amenities) as $amenity)
                            <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm font-medium capitalize border border-blue-100">
                                {{ $amenity }}
                            </span>
                        @endforeach
                    @else
                        <span class="text-gray-500 text-sm italic">Đang cập nhật...</span>
                    @endif
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h2 class="text-xl font-bold mb-2 text-gray-800">Mô tả</h2>
                    <p class="text-gray-600 leading-relaxed text-justify">
                        Trải nghiệm kỳ nghỉ tuyệt vời tại <strong>{{ $roomType->hotel->name }}</strong>. 
                        Hạng phòng này được thiết kế tinh tế với đầy đủ tiện nghi hiện đại, không gian thoáng đãng, phù hợp cho {{ $roomType->capacity }} người lớn. 
                        Tận hưởng dịch vụ đẳng cấp và sự thoải mái như ở nhà.
                    </p>
                </div>
            </div>
        </div>

        <div>
            <h1 class="text-3xl font-bold mb-2 text-gray-900">{{ $roomType->name }}</h1>
            <p class="text-gray-500 mb-6 text-lg flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                {{ $roomType->hotel->address }}
            </p>
            
            <div class="flex items-end gap-2 mb-6">
                <span class="text-3xl font-bold text-blue-600">{{ number_format($roomType->price_per_night) }} đ</span>
                <span class="text-gray-500 pb-1">/ đêm</span>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-200 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>
                
                <h3 class="text-xl font-bold mb-5 pb-2 text-gray-800 border-b">Thông tin đặt phòng</h3>
                
                <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
                    @csrf
                    <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                    <input type="hidden" name="hotel_id" value="{{ $roomType->hotel_id }}">
                    
                    <input type="hidden" id="pricePerNight" value="{{ $roomType->price_per_night }}">

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-sm font-bold mb-1 text-gray-700">Ngày nhận (Check-in)</label>
                            <input type="date" name="check_in" id="checkIn" required 
                                   value="{{ old('check_in') }}"
                                   class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1 text-gray-700">Ngày trả (Check-out)</label>
                            <input type="date" name="check_out" id="checkOut" required 
                                   value="{{ old('check_out') }}"
                                   class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition">
                        </div>
                    </div>
                    
                    <div class="mb-5">
                        <label class="block text-sm font-bold mb-1 text-gray-700">Mã giảm giá (Coupon)</label>
                        <div class="flex gap-2 relative">
                            <input type="text" name="promotion_code" id="promoCode" 
                                   class="w-full border border-gray-300 rounded-lg p-2.5 uppercase placeholder-gray-400 focus:ring-2 focus:ring-blue-500 outline-none pr-24" 
                                   placeholder="NHAPMA...">
                            <button type="button" onclick="applyCode()" 
                                    class="absolute right-1 top-1 bottom-1 bg-gray-800 text-white px-4 rounded-md text-sm hover:bg-black transition font-bold">
                                Áp dụng
                            </button>
                        </div>
                        <p id="promoMessage" class="text-xs mt-1.5 font-bold h-4"></p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold mb-1 text-gray-700">Ghi chú thêm</label>
                        <textarea name="notes" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none resize-none" 
                                  rows="2" placeholder="Ví dụ: Tôi muốn phòng tầng cao, check-in muộn...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-xl mb-6 text-sm space-y-3 border border-gray-100">
                        <div class="flex justify-between text-gray-600">
                            <span>Thời gian ở:</span>
                            <span id="showDays" class="font-bold text-gray-800">0 đêm</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Tạm tính:</span>
                            <span id="showTempPrice">0 đ</span>
                        </div>
                        <div class="flex justify-between text-green-600 font-bold hidden" id="discountRow">
                            <span>Giảm giá (Voucher):</span>
                            <span id="showDiscount">-0 đ</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3 mt-1">
                            <div class="flex justify-between items-end">
                                <span class="text-gray-800 font-bold text-base">Tổng thanh toán:</span>
                                <span id="showFinalPrice" class="text-2xl font-bold text-blue-600">0 đ</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-lg text-lg transition shadow-md hover:shadow-lg transform active:scale-95">
                        Xác nhận đặt phòng
                    </button>
                    
                    @guest
                        <div class="mt-3 text-center bg-yellow-50 text-yellow-800 text-xs py-2 rounded border border-yellow-200">
                            ⚠️ Bạn cần <strong>Đăng nhập</strong> để hoàn tất đặt phòng
                        </div>
                    @endguest
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Lấy các phần tử DOM cần thiết
    const checkInEl = document.getElementById('checkIn');
    const checkOutEl = document.getElementById('checkOut');
    const pricePerNight = parseInt(document.getElementById('pricePerNight').value);
    
    // Biến lưu trạng thái giảm giá hiện tại
    let currentDiscount = { type: null, value: 0 }; 

    // Hàm format tiền Việt
    function formatMoney(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    }

    // Hàm tính toán tổng tiền
    function calculateTotal() {
        const d1 = new Date(checkInEl.value);
        const d2 = new Date(checkOutEl.value);

        // Chỉ tính khi đã chọn cả 2 ngày
        if (checkInEl.value && checkOutEl.value) {
            
            // Logic chặn ngày trả trước ngày nhận
            if (d2 <= d1) {
                document.getElementById('showDays').innerText = "Ngày không hợp lệ";
                document.getElementById('showDays').className = "font-bold text-red-600";
                document.getElementById('showTempPrice').innerText = "---";
                document.getElementById('showFinalPrice').innerText = "---";
                return; 
            } else {
                document.getElementById('showDays').className = "font-bold text-gray-800";
            }

            // Tính số đêm
            const diffTime = Math.abs(d2 - d1);
            const days = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
            
            // Tính toán
            let tempPrice = days * pricePerNight;
            let discountAmount = 0;

            // Áp dụng giảm giá nếu có
            if (currentDiscount.type === 'percent') {
                discountAmount = tempPrice * (currentDiscount.value / 100);
            } else if (currentDiscount.type === 'amount') {
                discountAmount = currentDiscount.value;
            }

            // Đảm bảo không âm
            let finalPrice = tempPrice - discountAmount;
            if (finalPrice < 0) finalPrice = 0;

            // Hiển thị ra màn hình
            document.getElementById('showDays').innerText = days + ' đêm';
            document.getElementById('showTempPrice').innerText = formatMoney(tempPrice);
            document.getElementById('showFinalPrice').innerText = formatMoney(finalPrice);
            
            // Hiển thị/Ẩn dòng giảm giá
            if (discountAmount > 0) {
                document.getElementById('discountRow').classList.remove('hidden');
                document.getElementById('showDiscount').innerText = '-' + formatMoney(discountAmount);
            } else {
                document.getElementById('discountRow').classList.add('hidden');
            }
        }
    }

    // Gắn sự kiện: Khi đổi ngày thì tính lại tiền ngay
    checkInEl.addEventListener('change', calculateTotal);
    checkOutEl.addEventListener('change', calculateTotal);

    // Hàm gọi API check mã giảm giá
    function applyCode() {
        const code = document.getElementById('promoCode').value;
        const checkIn = checkInEl.value; // Cần ngày checkin để kiểm tra hạn sử dụng

        if(!code) {
            alert('Vui lòng nhập mã giảm giá!');
            return;
        }

        // Gọi AJAX lên Server
        fetch('{{ route("check.promotion") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                code: code, 
                check_in: checkIn 
            })
        })
        .then(response => response.json())
        .then(data => {
            const msgEl = document.getElementById('promoMessage');
            
            if(data.valid) {
                // Nếu mã đúng
                msgEl.innerText = data.message;
                msgEl.className = 'text-xs mt-1.5 font-bold text-green-600';
                
                // Lưu thông tin giảm giá vào biến
                if (data.discount_percent) {
                    currentDiscount = { type: 'percent', value: data.discount_percent };
                } else {
                    currentDiscount = { type: 'amount', value: parseInt(data.discount_amount) };
                }
                
                // Tính lại tiền ngay lập tức
                calculateTotal();
            } else {
                // Nếu mã sai
                msgEl.innerText = data.message;
                msgEl.className = 'text-xs mt-1.5 font-bold text-red-600';
                
                // Reset giảm giá
                currentDiscount = { type: null, value: 0 };
                calculateTotal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi kiểm tra mã!');
        });
    }
</script>
@endsection