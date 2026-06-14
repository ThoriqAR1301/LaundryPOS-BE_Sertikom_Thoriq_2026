<div align="center">

# 🧺 LaundryPOS — Backend API

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Sanctum](https://img.shields.io/badge/Laravel_Sanctum-API_Auth-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)

**REST API Backend Untuk Sistem Laundry Point Of Sales**
Dibangun Dengan Laravel 11 + Laravel Sanctum

</div>

---

## 📋 Daftar Isi

- [Tentang Proyek](#-tentang-proyek)
- [Teknologi](#-teknologi)
- [Struktur Database](#-struktur-database)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Menjalankan Server](#-menjalankan-server)
- [API Endpoints](#-api-endpoints)
- [Autentikasi](#-autentikasi)

---

## 🎯 Tentang Proyek

LaundryPOS Backend Adalah REST API Yang Dibangun Menggunakan **Laravel 11** Sebagai Tulang Punggung Sistem Ekosistem Laundry. API Ini Melayani Dua Klien Utama :

- **Web Admin Panel** — Untuk Petugas Mengelola Transaksi Dan Status Cucian
- **Mobile App (React Native)** — Untuk Pelanggan Memantau Status Cucian Secara Realtime

---

## 🛠 Teknologi

| Teknologi | Versi | Kegunaan |
|-----------|-------|----------|
| PHP | 8.2+ | Bahasa pemrograman |
| Laravel | 11.x | Framework Backend |
| Laravel Sanctum | 4.x | Autentikasi API Token |
| MySQL | 8.0 | Database |
| Laravel Storage | - | Upload & Manajemen File |

---

## 🗄 Struktur Database

```
users
├── id, name, email, password, plain_password, role (admin/customer)

customers
├── id, user_id (FK), phone, address

services
├── id, service_name, price, unit (Kg/Pcs)

transactions
├── id, invoice_code, admin_id (FK), customer_id (FK), service_id (FK)
├── service_unit, total_price, status (Enum)
├── payment_method, payment_status, payment_proof
├── paid_at, cloth_photo, deleted_at (soft delete)
```

---

## ⚙ Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- MySQL
- Node.js (Opsional, Untuk Asset)

### Langkah Instalasi

**1. Clone Repository**
```bash
git clone https://github.com/username/laundrypos-backend.git
cd laundrypos-backend
```

**2. Install Dependencies**
```bash
composer install
```

**3. Salin File Environment**
```bash
cp .env.example .env
```

**4. Generate Application Key**
```bash
php artisan key:generate
```

**5. Jalankan Migration & Seeder**
```bash
php artisan migrate
php artisan db:seed
```

**6. Buat Symbolic Link Storage**
```bash
php artisan storage:link
```

---

## 🔧 Konfigurasi

Sesuaikan File `.env` Dengan Konfigurasi Lokal Anda :

```env
APP_NAME=LaundryPOS
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laundry_pos
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

---

## 🚀 Menjalankan Server

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Server Berjalan Di : `http://localhost:8000`

---

## 📡 API Endpoints

### Authentication
| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| POST | `/api/login` | Login Pelanggan | ❌ |
| POST | `/api/logout` | Logout Pelanggan | ✅ |
| GET | `/api/profile` | Ambil Profil User | ✅ |
| PUT | `/api/profile` | Update Profil User | ✅ |

### Transaksi
| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| GET | `/api/status-laundry` | Status Cucian Milik Customer | ✅ |

### Customers
| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| GET | `/api/customers` | List Semua Pelanggan | ✅ |
| POST | `/api/customers` | Tambah Pelanggan | ✅ |
| GET | `/api/customers/{id}` | Detail Pelanggan | ✅ |
| PUT | `/api/customers/{id}` | Update Pelanggan | ✅ |
| DELETE | `/api/customers/{id}` | Hapus Pelanggan | ✅ |

### Services
| Method | Endpoint | Deskripsi | Auth |
|--------|----------|-----------|------|
| GET | `/api/services` | List Semua Layanan | ✅ |
| POST | `/api/services` | Tambah Layanan | ✅ |
| GET | `/api/services/{id}` | Detail Layanan | ✅ |
| PUT | `/api/services/{id}` | Update Layanan | ✅ |
| DELETE | `/api/services/{id}` | Hapus Layanan | ✅ |

---

## 🔐 Autentikasi

API Menggunakan **Laravel Sanctum** Dengan Bearer Token.

**Login :**
```json
POST /api/login
{
    "email": "customer@example.com",
    "password": "password123"
}
```

**Response :**
```json
{
    "status": true,
    "message": "Login Berhasil",
    "data": {
        "user": { ... },
        "token": "1|xxxxxxxxxxxxxxxx"
    }
}
```

**Gunakan Token Di Header Setiap Request :**
```
Authorization: Bearer 1|xxxxxxxxxxxxxxxx
```

---

## 👨‍💻 Developer

Dibuat Dengan ❤️ Untuk **Ujian Sertifikasi Kompetensi XI RPL**
Tahun Pelajaran 2025/2026

<div align="center">

# 🖥️ LaundryPOS — Web Admin Panel

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Chart.js](https://img.shields.io/badge/Chart.js-4.x-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)

**Dashboard Admin Untuk Sistem Laundry Point Of Sales**
Dibangun Dengan Laravel Blade + Tailwind CSS

</div>

---

## 📋 Daftar Isi

- [Tentang Proyek](#-tentang-proyek)
- [Fitur](#-fitur)
- [Teknologi](#-teknologi)
- [Instalasi](#-instalasi)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [Akun Default](#-akun-default)
- [Struktur Halaman](#-struktur-halaman)
- [Screenshots](#-screenshots)

---

## 🎯 Tentang Proyek

LaundryPOS Web Admin Panel Adalah Antarmuka Berbasis Web Untuk Petugas Laundry Dalam Mengelola Seluruh Operasional Bisnis — Mulai Dari Manajemen Pelanggan, Layanan, Transaksi, Hingga Laporan Keuangan.

---

## ✨ Fitur

### 🔐 Autentikasi
- Halaman **OnBoarding** 3 Slide Sebelum Login
- Login & Register Dengan Validasi
- Proteksi Route (Tidak Bisa Akses Tanpa Login)
- Auto Redirect Ke Login Saat Sesi Habis

### 📊 Dashboard
- Widget Ringkasan : Total Pendapatan, Total Transaksi, Status Cucian
- Grafik **Pendapatan Per Bulan** (Chart.js)
- Grafik **Transaksi Harian**
- **Layanan Terpopuler** Berdasarkan Total Order
- Status Pembayaran (Lunas vs Pending)
- Data Statistik Menggunakan **Soft Delete** Agar Angka Tetap Akurat

### 👥 Manajemen Pelanggan
- Tabel Pelanggan Dengan Pencarian (Nama/HP)
- Tambah, Edit, Dan Hapus Pelanggan
- Detail Pelanggan + Riwayat Transaksi
- Tampilan Password Dengan Toggle Show/Hide

### 🏷️ Manajemen Layanan
- CRUD Layanan Laundry (Kiloan/Satuan)
- Harga Per Unit Dengan Kalkulasi Otomatis

### 🧾 Manajemen Transaksi
- Form Transaksi Baru Dengan **Pencarian Pelanggan**
- Kalkulasi Total Harga Otomatis
- Upload **Foto Kondisi Baju** Saat Masuk
- Upload **Bukti Transfer** Pembayaran
- Update Status Cucian (Antrian → Dicuci → Disetrika → Siap Diambil → Selesai)
- **Cetak Struk** & **Download PDF**
- Progress Bar Alur Status Cucian
- Soft Delete Transaksi

### 📈 Laporan
- Laporan Pendapatan Per Bulan
- Filter Berdasarkan Periode

### 🎨 UI/UX
- **Dark Mode** Toggle
- **Loading Spinner** Di Setiap Aksi
- **Toast Notification** (Sukses/Gagal/Peringatan)
- **Modal Konfirmasi** Sebelum Hapus Data
- **Dropdown Notifikasi** Yang Menyimpan Riwayat Toast
- Live Clock & Tanggal Di Header
- Desain Responsif

---

## 🛠 Teknologi

| Teknologi | Kegunaan |
|-----------|----------|
| Laravel 11 (Blade) | Template Engine & Routing |
| Tailwind CSS (CDN) | Styling & Layout |
| Chart.js | Grafik Dashboard & Laporan |
| Font Awesome 6 | Ikon |
| Plus Jakarta Sans | Font |
| html2pdf.js | Generate PDF Struk |
| Laravel Sanctum | Autentikasi |

---

## ⚙ Instalasi

> Web Admin Panel Sudah **Menjadi Satu** Dengan Backend API Dalam Satu Project Laravel.
> Ikuti Langkah Instalasi Di [README Backend](../backend/README.md).

---

## 🚀 Menjalankan Aplikasi

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Buka Browser : `http://localhost:8000`

Saat Pertama Kali Dibuka Akan Muncul Halaman **OnBoarding** Terlebih Dahulu.

---

## 👤 Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@laundrypos.com | admin123 |

---

## 📄 Struktur Halaman

```
/                          → Redirect (Cek OnBoarding)
/onboarding                → Halaman OnBoarding (3 Slide)
/login                     → Halaman Login
/register                  → Halaman Register

/admin/dashboard           → Dashboard Utama
/admin/customers           → Daftar Pelanggan
/admin/customers/create    → Tambah Pelanggan
/admin/customers/{id}      → Detail Pelanggan
/admin/customers/{id}/edit → Edit Pelanggan
/admin/services            → Daftar Layanan
/admin/services/create     → Tambah Layanan
/admin/transactions        → Daftar Transaksi
/admin/transactions/create → Buat Transaksi Baru
/admin/transactions/{id}   → Detail Transaksi
/admin/reports             → Halaman Laporan
/admin/profile             → Profil Admin
```

---

## 👨‍💻 Developer

Dibuat Dengan ❤️ Untuk **Ujian Sertifikasi Kompetensi XI RPL**
Tahun Pelajaran 2025/2026
