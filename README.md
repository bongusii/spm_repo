# 🏨 Hotel Chain Management System (Laravel 12)

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

> **Hệ thống Quản lý Chuỗi Khách sạn** được xây dựng bằng Laravel 12, cung cấp giải pháp toàn diện từ đặt phòng (Booking) phía khách hàng đến quản trị (Admin Dashboard), phân quyền quản lý chi nhánh và báo cáo doanh thu.

---

| Trang chủ Khách hàng | Chi tiết phòng & Đặt chỗ |
|:---:|:---:|
| ![Home Page](https://via.placeholder.com/600x300?text=Home+Page+Screenshot) | ![Booking Page](https://via.placeholder.com/600x300?text=Booking+Screenshot) |

| Admin Dashboard | Hóa đơn PDF |
|:---:|:---:|
| ![Admin Dashboard](https://via.placeholder.com/600x300?text=Admin+Dashboard) | ![Invoice](https://via.placeholder.com/600x300?text=Invoice+PDF) |

---

## 🌟 Tính năng Chính (Key Features)

### 1. Phân hệ Khách hàng (Client Side)
- **Tìm kiếm thông minh:** Lọc phòng theo địa điểm, khoảng giá và **kiểm tra lịch trống** (tránh trùng lịch).
- **Đặt phòng (Booking):**
  - Tính tiền tự động theo số đêm.
  - Áp dụng **Mã giảm giá (Voucher/Coupon)** trực tiếp.
  - Kiểm tra tình trạng phòng thực tế (Real-time availability).
- **Tài khoản cá nhân:** Đăng ký, Đăng nhập, Xem lịch sử đặt phòng, Tự hủy đơn (khi chưa duyệt).

### 2. Phân hệ Quản trị (Admin Panel)
- **Phân quyền đa cấp (Multi-tenancy):**
  - **Super Admin:** Quản lý toàn bộ chuỗi, tạo khách sạn mới, gán quản lý.
  - **Branch Manager:** Chỉ thấy và quản lý dữ liệu (đơn hàng, phòng, doanh thu) của chi nhánh mình.
- **Quản lý Khách sạn & Phòng:**
  - Quản lý các chi nhánh khách sạn.
  - Quản lý Hạng phòng (Room Types) và Phòng vật lý (Physical Rooms).
  - **Sơ đồ phòng (Room Grid):** Xem trạng thái Trống/Có khách/Bảo trì bằng màu sắc trực quan.
- **Xử lý Đơn hàng (Workflow):**
  - Quy trình: Chờ duyệt -> Duyệt (Tự động gán phòng) -> Checkout (Trả phòng về trống).
  - **Xuất Hóa đơn (Invoicing):** Tạo hóa đơn chuẩn A4, in hoặc lưu PDF.
- **CRM & Marketing:**
  - Quản lý danh sách khách hàng, xem lịch sử chi tiêu.
  - Tạo mã khuyến mãi (Giảm theo % hoặc tiền mặt, set hạn sử dụng).
- **Báo cáo (Reports):**
  - Dashboard thống kê tổng quan.
  - **Biểu đồ doanh thu** theo tháng (sử dụng Chart.js).

---

## 🛠️ Công nghệ sử dụng (Tech Stack)

- **Backend:** PHP 8.2+, Laravel 12 Framework.
- **Frontend:** Blade Templates, TailwindCSS (CDN), Vanilla JS.
- **Database:** MySQL.
- **Khác:** Chart.js (Biểu đồ), Carbon (Xử lý thời gian).

---

## 🚀 Hướng dẫn Cài đặt (Installation)

Để chạy dự án này trên máy local, hãy làm theo các bước sau:

### 1. Clone dự án
```bash
git clone [https://github.com/USERNAME/REPO_NAME.git](https://github.com/USERNAME/REPO_NAME.git)
cd hotel-chain

### 2. Cài đặt các gói phụ thuộc
Bash

composer install
### 3. Cấu hình môi trường
Copy file .env.example thành .env và cấu hình thông số Database:

Bash

cp .env.example .env
Mở file .env và chỉnh sửa:

Code snippet

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotel-chain
DB_USERNAME=root          
DB_PASSWORD=                
### 4. Tạo Key và Database
Bash

php artisan key:generate
php artisan migrate
(Tùy chọn: Nếu có seeders) php artisan db:seed
