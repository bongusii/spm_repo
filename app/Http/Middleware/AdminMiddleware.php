<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Kiểm tra đã đăng nhập chưa?
        // 2. Kiểm tra role có phải là sếp (super_admin hoặc branch_manager) không?
        if (Auth::check() && (Auth::user()->role === 'super_admin' || Auth::user()->role === 'branch_manager')) {
            return $next($request); // Cho phép đi tiếp
        }

        // Nếu không phải admin thì chặn lại
        abort(403, 'Đi chỗ khác chơi!');
    }
}
