@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Tổng quan hệ thống')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm font-medium uppercase">Tổng Doanh Thu</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalRevenue) }} đ</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full text-green-600">
                    💰
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-yellow-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm font-medium uppercase">Đơn Cần Duyệt</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $newBookings }}</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full text-yellow-600">
                    🔔
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm font-medium uppercase">Khách Hàng</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalCustomers }}</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full text-blue-600">
                    👥
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm font-medium uppercase">Tổng Phòng</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalRooms }}</h3>
                </div>
                <div class="bg-purple-100 p-3 rounded-full text-purple-600">
                    🏨
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Biểu đồ doanh thu năm nay</h3>
        <div class="relative h-80 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart');

        new Chart(ctx, {
            type: 'bar', // Loại biểu đồ: bar (cột), line (đường), pie (tròn)
            data: {
                labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: @json($chartData), // Lấy dữ liệu từ Laravel Controller
                    backgroundColor: 'rgba(59, 130, 246, 0.5)', // Màu cột (Xanh dương)
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('vi-VN') + ' đ'; // Format tiền trục tung
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection