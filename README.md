# 🏛️ Web Kuisioner & Portal Digital - Disarpus Lombok Barat

Portal Layanan Digital resmi Dinas Kearsipan dan Perpustakaan Kabupaten Lombok Barat. Aplikasi ini dirancang untuk memfasilitasi pengukuran Indeks Pembangunan Literasi Masyarakat (IPLM), Tingkat Kegemaran Membaca (TKM), serta menyediakan jalur pengaduan aspirasi masyarakat secara terpadu.

---

## 🚀 Fitur Unggulan

### 🛡️ Keamanan & Akses Kontrol
- **Role-Based Access Control (RBAC)**: Pembagian akses antara `Super Admin` dan `Admin` biasa.
- **Primary Admin Protection**: Proteksi tingkat tinggi untuk akun Admin Utama (Owner) yang tidak dapat dihapus melalui sistem web.
- **CSRF Protection**: Melindungi formulir dari serangan *Cross-Site Request Forgery*.
- **Rate Limiting**: Keamanan pada sistem login dan reset password untuk mencegah *brute force*.
- **Secure Configuration**: Proteksi file sensitif menggunakan `.htaccess`.

### 📧 Sistem Notifikasi & Reset Password
- **Brevo API Integration**: Pengiriman email reset password yang stabil menggunakan API HTTP (menggantikan SMTP tradisional yang sering terblokir).
- **Email Logging**: Riwayat pengiriman email yang dapat dipantau langsung dari dashboard admin.

### 📊 Manajemen Data
- **Dynamic Survey Engine**: Rendering kuisioner secara dinamis berdasarkan data database.
- **Data Export**: Ekspor hasil survei ke format Excel (XLS) untuk laporan formal.
- **System Logs**: Pencatatan riwayat reset password demi akuntabilitas.

### 🔍 Optimasi Mesin Pencari (SEO)
- **SEO Meta Tags**: Optimasi deskripsi dan kata kunci untuk Google.
- **Open Graph Ready**: Tampilan profesional saat link dibagikan ke media sosial (WhatsApp, FB, dll).
- **Robots.txt**: Mengatur akses robot pencari agar hanya indeks halaman publik.

---

## 🛠️ Tech Stack
- **Backend**: PHP 8.x
- **Database**: MySQL / MariaDB
- **UI/UX**: Bootstrap 5 + Vanilla CSS (Glassmorphism Concept)
- **Library**: Brevo API v3 (Mail Delivery)
- **Environment**: Laragon (Recommended for local dev)

---

## 📦 Panduan Instalasi Lokal

1. **Clone Proyek**
   ```bash
   git clone https://github.com/kevingathfan/web-kuisioner-disarpuslobar.git
   ```

2. **Setup Database**
   - Buat database baru bernama `monitoring_perpus_db`.
   - Import file `database.sql` ke database tersebut.

3. **Konfigurasi Database**
   Sesuaikan kredensial database Anda di file `config/database.php`.

4. **Konfigurasi Email (Brevo API)**
   - Duplikat file `config/mail_config.sample.php` menjadi `config/mail_config.php`.
   - Masukkan **Brevo API Key** Anda pada kolom `brevo_api_key`.

5. **Akses Aplikasi**
   - **Publik**: `http://localhost/web-perpus-lobar/`
   - **Admin**: `http://localhost/web-perpus-lobar/admin/login.php`

---

## 🔐 Akun Admin Default
- Akun admin tersimpan di tabel `users`.
- Pastikan salah satu akun memiliki nilai `is_primary = 1` di database untuk mendapatkan proteksi penuh sebagai Admin Utama.

---

## 📄 Lisensi
Internal Project - Dinas Kearsipan dan Perpustakaan Kabupaten Lombok Barat.
