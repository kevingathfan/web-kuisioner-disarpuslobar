<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link / Kode Tidak Valid - DISARPUS</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/loader.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .main-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 50px 40px;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04);
        }
        .icon-box {
            width: 72px; height: 72px;
            background: #FEF2F2; color: #EF4444;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.2rem;
            margin: 0 auto 24px;
            border: 1px solid #FEE2E2;
        }
        h2 { font-size: 1.4rem; color: #0F172A; margin-bottom: 12px; font-weight: 800; }
        p { color: #64748B; font-size: 0.95rem; margin-bottom: 2rem; line-height: 1.6; }
        .btn-retry {
            background: #2563EB; color: white;
            padding: 12px 28px; border-radius: 50px;
            font-weight: 700; text-decoration: none;
            transition: 0.2s; display: inline-flex;
            align-items: center; justify-content: center; gap: 8px; font-size: 0.9rem;
            width: 100%; margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }
        .btn-retry:hover { background: #1E40AF; transform: translateY(-1px); color: white; }
        .btn-back {
            background: #F1F5F9; color: #475569;
            padding: 12px 28px; border-radius: 50px;
            font-weight: 700; text-decoration: none;
            transition: 0.2s; display: inline-flex;
            align-items: center; justify-content: center; gap: 8px; font-size: 0.9rem;
            width: 100%;
        }
        .btn-back:hover { background: #E2E8F0; color: #0F172A; }
    </style>
</head>
<body>
    <?php include BASE_PATH . 'config/loader.php'; ?>
    <div class="main-card">
        <div class="icon-box"><i class="bi bi-shield-exclamation"></i></div>
        <h2>Kode Akses Tidak Valid</h2>
        <p>Link atau Kode Akses yang Anda masukkan tidak ditemukan atau sudah tidak berlaku. Silakan periksa kembali atau hubungi Administrator Disarpus Lombok Barat untuk mendapatkan akses resmi.</p>
        
        <a href="<?= BASE_URL ?>/pustakawan/pilih_perpustakaan?target=iplm" class="btn-retry">
            <i class="bi bi-arrow-repeat"></i> Coba Masukkan Kode Lagi
        </a>
        <a href="<?= BASE_URL ?>" class="btn-back">
            <i class="bi bi-house"></i> Kembali ke Beranda
        </a>
    </div>
    <script src="<?= BASE_URL ?>/assets/loader.js"></script>
</body>
</html>
