# Penjelasan Keadaan Sistem dan Detail Aplikasi
**Layanan Survei & Kotak Aspirasi Dinas Kearsipan & Perpustakaan Kabupaten Lombok Barat (DISARPUS LOBAR)**

Dokumen ini menyajikan analisis menyeluruh mengenai kondisi terkini, arsitektur sistem, skema database, katalog fitur, mekanisme keamanan, serta panduan estetika visual dari aplikasi **Royal GovTech DISARPUS LOBAR**.

---

## 🗺️ Entity-Relationship Diagram (ERD)

Berikut adalah struktur hubungan antar tabel (skema relasional) yang diimplementasikan pada database `monitoring_perpus_db`:

```mermaid
erDiagram
    users {
        int id PK
        varchar nama UK
        varchar email UK
        enum role "super, admin"
        text password
        timestamp created_at
        tinyint is_primary
    }

    libraries {
        int id PK
        varchar nama
        varchar jenis
        text lokasi
        varchar kategori
        timestamp created_at
    }

    master_kategori {
        int id PK
        varchar kategori
        varchar sub_kategori
    }

    kategori_bagian {
        int id PK
        varchar jenis_kuesioner
        varchar name
        int position
        enum numbering_style "numeric, roman, none"
        varchar manual_label
        timestamp created_at
    }

    master_pertanyaan {
        int id PK
        varchar jenis_kuesioner
        varchar kategori_bagian
        text teks_pertanyaan
        varchar tipe_input
        text opsi_jawaban "deprecated"
        text pilihan_opsi
        int urutan
        int is_active
        text keterangan
    }

    trans_header {
        int id PK
        int library_id FK
        varchar jenis_kuesioner
        varchar periode_bulan
        varchar periode_tahun
        timestamp tanggal_isi
    }

    trans_detail {
        int id PK
        int header_id FK
        int pertanyaan_id FK
        text jawaban
    }

    pengaduan {
        int id PK
        varchar nama
        varchar kontak
        text pesan
        date tanggal
        timestamp created_at
        tinyint is_important
        tinyint is_done
    }

    settings {
        varchar setting_key PK
        varchar setting_value
    }

    password_resets {
        int id PK
        int user_id FK
        varchar token_hash
        timestamp expires_at
        timestamp used_at
        timestamp created_at
    }

    password_reset_logs {
        int id PK
        int user_id FK
        varchar ip_address
        timestamp created_at
    }

    password_reset_email_logs {
        int id PK
        varchar email
        varchar status
        text error_message
        varchar token_hash
        timestamp expires_at
        timestamp created_at
    }

    libraries }|--|| master_kategori : "kategori & jenis matches"
    trans_header }|--|| libraries : "belongs to (optional)"
    trans_detail }|--|| trans_header : "belongs to (cascade)"
    trans_detail }|--|| master_pertanyaan : "answers (cascade)"
    master_pertanyaan }|--|| kategori_bagian : "grouped by (name & jenis)"
    password_resets }|--|| users : "requested by"
    password_reset_logs }|--|| users : "logs for"
```

---

## 🏛️ Arsitektur & Teknologi Utama

Sistem dikembangkan dengan arsitektur **monolitik modular berbasis web** yang sangat efisien dan aman tanpa ketergantungan framework berat.

### 💻 Stack Teknologi
1. **Core Backend**: PHP 8.x Native (menggunakan OOP PDO untuk koneksi database yang aman).
2. **Database Engine**: MySQL / MariaDB (Storage Engine: InnoDB dengan integritas referensial kunci asing dan aksi `ON DELETE CASCADE`).
3. **Frontend Presentation**: HTML5, Vanilla CSS3 (Custom Responsive & Glassmorphism Tokens), Bootstrap 5.3 (Utility framework), JQuery (untuk pemrosesan AJAX yang ringan).
4. **Library & Integrasi Pihak Ketiga**:
   - **SweetAlert2**: Untuk tampilan dialog konfirmasi modern dan elegan.
   - **Select2 (Bootstrap 5 Theme)**: Untuk pencarian interaktif nama perpustakaan secara dinamis.
   - **Chart.js**: Render statistik grafik bulanan interaktif pada dashboard admin.
   - **PHPMailer / Brevo API Integration**: Otentikasi pengiriman email pemulihan sandi secara profesional.

---

## 📂 Struktur Berkas & Direktori Workspace

Workspace tersusun secara modular dengan pemisahan tegas antara logika admin, publik/pustakawan, dan konfigurasi inti:

```
web-perpus-lobar/
├── .htaccess                 # Konfigurasi Apache (Security Headers, Gzip Compression, dan Clean Route redirection)
├── index.php                 # Halaman Gerbang Utama (Landing Page dengan Glassmorphism Premium)
├── proses_simpan.php         # Script penampung & validator penyimpanan data kuesioner publik
├── database.sql              # Berkas eksport data SQL komplit skema & master data terbaru
├── config/                   # Konfigurasi Inti & Utility Logika
│   ├── admin_auth.php        # Middleware otentikasi admin, pengecekan Session Timeout (30 Menit), & Admin CSRF
│   ├── database.php          # Konektor PDO untuk lingkungan Localhost
│   ├── database_hosting.php  # Konektor PDO untuk lingkungan Production Hosting
│   ├── loader.php            # Markup HTML spinner loading transisi halaman
│   ├── mail_config.php       # Parameter rahasia otentikasi SMTP/API Brevo
│   ├── mailer.php            # Wrapper pengiriman email pemulihan kata sandi
│   ├── profanity.php         # Kamus data kata-kata kasar/umpatan sensor pengaduan
│   └── public_security.php   # Middleware CSRF Publik, dan Rate Limiting (File-Locking based)
├── assets/                   # Aset Statis & Gaya Visual (CSS & Gambar)
│   ├── admin-readability.css # Optimasi kontras teks dan keterbacaan font di dashboard admin
│   ├── admin-responsive.css  # Media Query penyesuaian sidebar/tabel pada gawai mobile
│   ├── govtech.css           # Tema warna utama Royal GovTech, Grid, dan Card Tokens
│   ├── loader.css / js       # Animasi CSS spinner penjelajah transisi
│   ├── logo_disarpus.png     # Logo resmi Dinas Kearsipan & Perpustakaan Lombok Barat
│   ├── logo_lobar.png        # Logo resmi Kabupaten Lombok Barat
│   └── public-responsive.css # Media Query responsivitas untuk form publik
├── pustakawan/               # Portal Pengisian & Layanan Publik
│   ├── beranda.php           # Landing Page khusus pengisi survei
│   ├── pilih_perpustakaan.php# Form interaktif pemilihan Jenis, Sub Jenis, & Nama Perpustakaan
│   ├── kuisioner_iplm.php    # Pembungkus form dinamis IPLM
│   ├── kuisioner_tkm.php     # Pembungkus form dinamis TKM
│   ├── render_kuesioner.php  # Render dinamis Pertanyaan dari DB, Minimap Navigasi, & Auto-Scroll
│   ├── form_pengaduan.php    # Form Kotak Aspirasi publik
│   ├── proses_pengaduan.php  # Penampung complaint & filter profanitas/umpatan kasar
│   └── riwayat.php / profil.php # Riwayat pengisian data lokal
└── admin/                    # Panel Dashboard Administrator (RBAC & Analisis)
    ├── index.php             # Proteksi folder admin
    ├── login.php / logout.php# Logika autentikasi formal administrator
    ├── forgot_password.php   # Form pemulihan password via token email
    ├── reset_password.php    # Halaman input password baru pasca verifikasi token
    ├── dashboard.php         # Beranda statistik analitis, filter dinamis, dan scheduling survei
    ├── perpustakaan.php      # Manajemen data unit perpustakaan (CRUD, live search, reset status, import CSV)
    ├── atur_pertanyaan.php   # Manajemen kuesioner, urutan dinamis, numbering style, impor soal CSV, auto-fill
    ├── hasil_kuisioner.php   # Rekapitulasi jawaban, tabel matriks, filter periodik, export data
    ├── pengaduan.php         # Manajemen kotak aspirasi (pin, status penyelesaian, sensor teks kasar)
    ├── users.php             # Manajemen admin (Super vs Standard), dengan sistem "Non-Deletable Primary Admin"
    └── export_data.php       # Utilitas export rekapitulasi data format Excel (Spreadsheet)
```

---

## 🌟 Inventaris Fitur & Fungsionalitas Aplikasi

### 1. Portal Publik & Pengisian Kuesioner (`pustakawan/`)
- **Peta Identitas Perpustakaan (`pilih_perpustakaan.php`)**: Alur berjenjang (Jenis Utama $\rightarrow$ Sub Jenis $\rightarrow$ Cari Nama Perpustakaan) dengan Select2 interaktif. Mencegah kesalahan ketik (typo) nama instansi oleh responden.
- **Formulir Kuesioner Dinamis (`render_kuesioner.php`)**:
  - Mengambil data pertanyaan langsung dari database sesuai jenis survei (IPLM / TKM).
  - Mengelompokkan otomatis pertanyaan ke dalam panel seksi (`section-card`) berdasarkan `kategori_bagian`.
  - **Peta Navigasi Soal (Minimap)**: Menampilkan status keterisian pertanyaan. Dot hijau menandakan terisi, dot biru menandakan aktif, dot abu-abu menandakan kosong. Memungkinkan loncat navigasi instan (`jumpTo`) ke pertanyaan tertentu.
  - **Auto-Scroll & Keyboard Focus**: Setelah memilih opsi (radio/likert) atau menekan Enter di kolom teks, sistem otomatis melakukan gulir halus (smooth scroll) ke pertanyaan berikutnya.
  - **Draft Persistence (`localStorage`)**: Jawaban tersimpan otomatis di browser secara berkala. Jika internet terputus atau tab tertutup tidak sengaja, jawaban tidak akan hilang saat dimuat kembali.
  - **Penomoran Dinamis**: Penomoran bab dan butir pertanyaan menggunakan gaya penomoran global dari database (Angka, Romawi, atau Tanpa Nomor).
- **Kotak Aspirasi / Pengaduan (`form_pengaduan.php`)**: Portal pengaduan resmi terintegrasi dengan filter kata-kata kasar.

### 2. Panel Administrator (`admin/`)
- **Dashboard Analitis (`dashboard.php`)**:
  - Ringkasan total responden bulanan, jumlah instansi yang sudah berpartisipasi, dan yang belum berpartisipasi.
  - Grafik tren bulanan IPLM & TKM interaktif berbasis Chart.js.
  - **Penjadwalan Kuesioner Otomatis**: Fitur buka/tutup survei secara otomatis berdasarkan rentang tanggal dan jam terprogram, atau dialihkan ke mode manual secara instan.
- **Manajemen Perpustakaan (`perpustakaan.php`)**:
  - Tambah, ubah, dan hapus instansi perpustakaan secara live (AJAX-based search).
  - **Reset Status Pengisian**: Membatalkan status pengisian instansi tertentu untuk bulan berjalan jika terjadi kesalahan pengisian (menggunakan transaksi DB terisolasi).
  - **Impor Massal Instansi**: Unggah data ribuan perpustakaan instan menggunakan file CSV dengan pembatas dinamis (koma `,` atau titik koma `;`) dan validasi duplikasi ketat.
- **Manajemen Kuesioner (`atur_pertanyaan.php`)**:
  - Menambah seksi/bagian kuesioner lengkap dengan pengaturan posisinya secara drag-less splice.
  - Menambah dan mengubah pertanyaan satu per satu atau **mengunggah massal via CSV**.
  - **Auto-Order Refresh**: Menjaga urutan pertanyaan selalu berurutan (tanpa lubang nomor urut) per seksi kuesioner. Jika satu soal dihapus atau urutannya disisipkan di tengah, sistem akan menyesuaikan nomor urutan lainnya secara otomatis.
  - **Auto-Fill Config**: Memetakan id pertanyaan tertentu untuk jenis, sub-jenis, dan nama perpustakaan agar terisi otomatis saat responden membuka formulir kuesioner IPLM.
- **Manajemen Hasil Kuesioner (`hasil_kuisioner.php`)**:
  - Rekapitulasi tabulasi dinamis seluruh jawaban responden.
  - **Ekspor Excel Profesional**: Menghasilkan file excel tabulasi komplit dengan baris jawaban responden per butir soal untuk diolah lebih lanjut.
- **Manajemen Pengaduan (`pengaduan.php`)**:
  - Menandai laporan pengaduan penting (pin ke atas) atau menandainya selesai diproses.
- **Manajemen Pengguna (`users.php`)**:
  - Pembatasan hak akses berbasis peran (Super Admin vs Admin Standar). Super Admin dapat mengelola akun admin lain.

---

## 🔒 Sistem Keamanan Hardening & Validasi

Aplikasi ini telah melewati fase audit dan pengerasan keamanan (Security Hardening) tingkat tinggi:

1. **Proteksi CSRF (Cross-Site Request Forgery)**:
   - Token CSRF acak unik dihasilkan per sesi untuk admin (`config/admin_auth.php`) maupun publik (`config/public_security.php`).
   - Setiap permintaan POST divalidasi ketat menggunakan fungsi pembanding tahan-waktu (`hash_equals`).
2. **Rate Limiting Anti Brute Force / DDoS**:
   - Limit pengiriman kuesioner dibatasi maksimal **20 kali per jam** per alamat IP.
   - Limit pengiriman kotak aspirasi dibatasi maksimal **5 kali per jam** per alamat IP.
   - Menggunakan mekanisme file-locking (`LOCK_EX`) terenkripsi untuk mencegah race conditions saat pencatatan rate limit.
3. **Penyaringan Konten Kasar (Profanity Filter)**:
   - Seluruh input pengaduan dipindai menggunakan kamus ekspresi reguler (`config/profanity.php`).
   - Kata-kata kasar/umpatan disensor otomatis menjadi `***` sebelum masuk ke database, dengan pemberitahuan edukatif yang sopan kepada pengirim.
4. **Validasi Anti Duplikasi Ganda (IPLM)**:
   - **Duplikasi Instansi**: Unit perpustakaan yang sama tidak diperkenankan mengisi formulir IPLM lebih dari satu kali pada bulan dan tahun periode berjalan.
   - **Duplikasi Kontak Pengisi**: Kontak pengisi (Nomor HP/WhatsApp/Email) yang sama tidak boleh digunakan untuk mengisi IPLM pada perpustakaan yang berbeda dalam bulan/tahun berjalan. Nomor telepon dinormalisasi terlebih dahulu dengan menghapus semua karakter non-angka agar pendeteksian duplikat tetap akurat meski format penulisannya berbeda (misal: `081...`, `+6281...`, `62-81...`).
5. **Session Security & Timeout**:
   - Sesi administrator otomatis dihancurkan (`session_destroy`) jika tidak terdeteksi adanya aktivitas (idle) selama **30 menit** berturut-turut untuk mencegah pembajakan sesi di komputer bersama.
6. **Perlindungan Hak Akses Utama (Primary Admin protection)**:
   - Akun Super Admin pertama bertindak sebagai "Primary Admin" (`is_primary = 1`).
   - Akun ini terlindungi secara permanen dari upaya penghapusan, penggantian email, maupun penurunan peran (demotion) oleh admin super lainnya.
7. **Pencegahan Kebocoran Database (Safe DB Log)**:
   - Pesan kesalahan (SQL error) diisolasi total dari pengguna publik. Sistem hanya menampilkan pesan ramah umum, sedangkan pesan kesalahan teknis dicatat di server log privat menggunakan `error_log()`.

---

## 🎨 Panduan Estetika Visual (Royal GovTech)

Antarmuka dirancang menggunakan filosofi **Royal GovTech UI** yang elegan, berwibawa, namun terasa sangat modern dan interaktif:

### 🎨 Palet Warna Kurasi
- **Primary Color**: `#0F52BA` (Royal Blue) $\rightarrow$ Mewakili wibawa kepemerintahan dan kepercayaan publik.
- **Primary Dark**: `#0a3d8f` (Deep Cobalt) $\rightarrow$ Memberikan kontras mendalam untuk teks tajam.
- **Accent Color**: `#334155` (Slate Gray) $\rightarrow$ Memberikan kesan tenang pada navigasi sekunder.
- **Background Slate**: `#f8fafc` $\rightarrow$ Latar belakang bersih yang meminimalkan kelelahan mata.
- **Border Utility**: `#e2e8f0` $\rightarrow$ Garis pembatas tipis yang memberikan struktur rapi tanpa terlihat kaku.

### ✨ Karakteristik UI & Efek Visual
- **Glassmorphism Premium**: Penggunaan latar belakang semi-transparan `rgba(255, 255, 255, 0.9)` berpadu dengan efek blur kaca `backdrop-filter: blur(12px)` pada kartu portal utama.
- **Pola Geometris Dinamis**: Latar belakang dihiasi gradasi lingkaran radial tipis (`radial-gradient`) dikombinasikan dengan garis grid transparan 40px, menciptakan ilusi kedalaman visual yang memukau.
- **Tipografi Modern**: Sepenuhnya menggunakan font **Plus Jakarta Sans** (Google Fonts) dengan penataan ketebalan ekstrim (`font-weight: 800`) untuk penekanan tajam judul halaman.
- **Aksentuasi Tombol**: Tombol tindakan utama menggunakan gradasi linear `linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%)` berhias bayangan lembut (`box-shadow`), serta efek mikro-interaksi melayang (hover translate-Y -2px).

---

## 📈 Kondisi Terkini & Kesimpulan Proyek

Aplikasi **Layanan Survei & Kotak Aspirasi DISARPUS LOBAR** saat ini berada dalam kondisi **siap produksi (Production Ready)** dengan performa maksimal, pertahanan keamanan yang andal, dan desain visual yang elegan. Seluruh fitur administrasi (CRUD, import massal, ekspor rekapitulasi, penjadwalan dinamis, sistem otentikasi reset sandi via Brevo API, perlindungan role RBAC) berjalan dengan benar dan stabil.

Sistem ini siap dihosting pada server produksi Dinas Kearsipan & Perpustakaan Lombok Barat untuk memfasilitasi pengumpulan data indeks literasi yang andal dan aman.
