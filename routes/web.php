<?php

use Illuminate\Support\Facades\Route;

// Import các Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminBookingController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminCustomerController;
use App\Http\Controllers\AdminPromotionController;
use App\Http\Controllers\AdminInvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. ROUTE CÔNG KHAI (Public) ---

// Trang chủ & Tìm kiếm
Route::get('/', [HomeController::class, 'index'])->name('home');

// Chi tiết phòng
Route::get('/room/{id}', [HomeController::class, 'show'])->name('room.show');

// --- 2. ROUTE XÁC THỰC (Authentication) ---

// Dành cho khách CHƯA đăng nhập (Guest)
Route::middleware('guest')->group(function () {
    // Đăng ký
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Đăng nhập
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Dành cho khách ĐÃ đăng nhập
Route::middleware('auth')->group(function () {
    // Đăng xuất
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Xử lý Đặt phòng (Booking)
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

    // Xem lịch sử đặt phòng & Hủy đơn
    Route::get('/my-bookings', [BookingController::class, 'index'])->name('my-bookings.index');
    Route::post('/my-bookings/cancel/{id}', [BookingController::class, 'cancel'])->name('my-bookings.cancel');

    Route::post('/check-promotion', [BookingController::class, 'checkPromotion'])->name('check.promotion');
});

// --- 3. ROUTE QUẢN TRỊ (Admin) ---

// Quản lý Khách sạn, Loại phòng, Phòng vật lý
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    
    // prefix('admin') để đường dẫn đẹp hơn: /admin/hotels

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Quản lý Khách sạn
    Route::resource('hotels', HotelController::class);
    
    // Quản lý Loại phòng
    Route::resource('room-types', RoomTypeController::class);
    
    // Quản lý Phòng
    Route::resource('rooms', RoomController::class);

    // Quản lý Đơn đặt phòng
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
    Route::patch('/bookings/{id}', [AdminBookingController::class, 'updateStatus'])->name('admin.bookings.update');

    // Quản lý Khách hàng
    Route::resource('customers', AdminCustomerController::class)->only(['index', 'show', 'destroy']);

    // Quản lý Khuyến mãi
    Route::resource('promotions', AdminPromotionController::class);

    // Tạo hóa đơn từ Booking ID
    Route::get('/invoices/generate/{bookingId}', [AdminInvoiceController::class, 'generate'])->name('invoices.generate');
    
    // Xem chi tiết hóa đơn
    Route::get('/invoices/{id}', [AdminInvoiceController::class, 'show'])->name('invoices.show');
});