# 🏛️ SAPA Paramadina
**Sistem Akses Peminjaman Fasilitas Umum Universitas Paramadina**

SAPA adalah aplikasi berbasis web yang dirancang untuk memudahkan mahasiswa Universitas Paramadina dalam meminjam fasilitas kampus seperti ruangan, alat laboratorium, dan perangkat elektronik. Dibangun dengan pendekatan *Mobile-First Design*, SAPA menawarkan UI/UX yang modern layaknya aplikasi *native*.

---

## ✨ Fitur Utama

### 🎓 Mahasiswa (User)
*   **SSO Google Login:** Masuk dengan aman menggunakan akun Google kampus (`@paramadina.ac.id`).
*   **Mobile-Native Dashboard:** Tampilan interaktif dengan *Quick Actions* dan notifikasi denda *real-time*.
*   **QR Code Scanner:** Memindai QR Code di pintu ruangan lab langsung dari kamera HP (*in-browser*).
*   **Multi-Borrowing (Cart System):** Meminjam beberapa aset sekaligus dalam satu ruangan.
*   **Katalog & Filter:** Mencari fasilitas secara spesifik dengan sistem *filter* kategori yang cepat.
*   **Manajemen Riwayat:** Melacak status peminjaman (Aktif, Selesai, Telat) dan tagihan denda keterlambatan.

### 🛡️ Administrator (Admin)
*   **Master Data:** Manajemen data Ruangan, Aset (Stok & Kondisi), Kategori, dan Program Studi.
*   **Approval System:** Menyetujui atau menolak permintaan peminjaman.
*   **Manajemen Denda:** Melacak tunggakan denda mahasiswa dan mengonfirmasi pembayaran.
*   **User & Role Management:** Mengatur hak akses pengguna sistem.

---

## 🛠️ Teknologi yang Digunakan
*   **Framework:** Laravel (PHP)
*   **Frontend:** Blade Templating, Tailwind CSS
*   **Database:** MySQL
*   **JavaScript Libraries:** 
    *   `html5-qrcode` (Untuk fitur Scanner Kamera)
    *   `SweetAlert2` (Untuk popup notifikasi elegan)
*   **Icons & Fonts:** FontAwesome 6 & Plus Jakarta Sans

---

## ⚙️ Persyaratan Sistem (Prerequisites)

Sebelum melakukan instalasi, pastikan sistem Anda memiliki:
*   PHP >= 8.1
*   Composer
*   Node.js & NPM
*   MySQL / MariaDB
*   Kredensial Google OAuth 2.0 (Google Cloud Console)

---

## 🚀 Panduan Instalasi (Installation Guide)

Ikuti langkah-langkah berikut untuk menjalankan aplikasi SAPA di komputer lokal (Localhost):

### 1. Clone Repository
Buka terminal/CMD dan jalankan perintah berikut untuk mengunduh kode sumber:
```bash
git clone [https://github.com/username-anda/sapa-paramadina.git](https://github.com/username-anda/sapa-paramadina.git)
cd sapa-paramadina

2. Install Dependencies
Instal semua package PHP dan library Frontend yang dibutuhkan:

Bash
composer install
npm install


3. Konfigurasi Environment (.env)
Salin file konfigurasi environment bawaan:

Bash
cp .env.example .env
Buka file .env di text editor Anda, lalu sesuaikan konfigurasi Database dan Google OAuth:

Cuplikan kode
# Konfigurasi Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sapaparamadina
DB_USERNAME=root
DB_PASSWORD=

# Konfigurasi Google Login (SSO)
GOOGLE_CLIENT_ID=masukkan_client_id_google_anda_disini
GOOGLE_CLIENT_SECRET=masukkan_client_secret_google_anda_disini
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

4. Generate App Key & Link Storage
Buat kunci keamanan aplikasi dan tautkan folder storage agar gambar aset bisa ditampilkan:

Bash
php artisan key:generate
php artisan storage:link


5. Migrasi & Seeding Database
Jalankan migrasi untuk membuat tabel di database, beserta seeder untuk mengisi data awal (akun admin, kategori, dll):

Bash
php artisan migrate --seed