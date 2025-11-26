<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Gọi Model User
use Illuminate\Support\Facades\Auth; // Gọi thư viện Auth của Laravel
use Illuminate\Support\Facades\Hash; // Để mã hóa mật khẩu

class AuthController extends Controller
{
    // --- ĐĂNG KÝ (REGISTER) ---
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Email không được trùng
            'password' => 'required|min:6|confirmed', // password_confirmation phải khớp
            'phone' => 'nullable|string'
        ]);

        // 2. Tạo User mới
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Bắt buộc phải mã hóa pass
            'phone' => $request->phone,
            'role' => 'customer', // Mặc định là khách hàng
        ]);

        // 3. Đăng nhập luôn cho họ sau khi đăng ký thành công
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Đăng ký thành công! Chào mừng bạn.');
    }

    // --- ĐĂNG NHẬP (LOGIN) ---
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validate
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Thử đăng nhập (Auth::attempt tự động so sánh hash password)
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate(); // Bảo mật session

            // Nếu là Admin thì vào trang Admin, Khách thì về trang chủ
            if (Auth::user()->role === 'super_admin' || Auth::user()->role === 'branch_manager') {
                return redirect()->route('hotels.index');
            }

            return redirect()->intended('/'); // Về trang trước đó hoặc trang chủ
        }

        // 3. Nếu sai thì quay lại form và báo lỗi
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ]);
    }

    // --- ĐĂNG XUẤT (LOGOUT) ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}