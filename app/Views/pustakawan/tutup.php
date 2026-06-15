<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditutup - DISARPUS</title>
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
            overflow: hidden;
        }
        .main-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 50px 40px;
            max-width: 460px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04);
        }
        .icon-box {
            width: 72px; height: 72px;
            background: #FEF2F2; color: #EF4444;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem;
            margin: 0 auto 24px;
            border: 1px solid #FEE2E2;
        }
        h2 { font-size: 1.35rem; color: #0F172A; margin-bottom: 8px; }
        p { color: #64748B; font-size: 0.92rem; margin-bottom: 2rem; line-height: 1.6; }
        .btn-back {
            background: #0F172A; color: white;
            padding: 12px 28px; border-radius: 50px;
            font-weight: 700; text-decoration: none;
            transition: 0.2s; display: inline-flex;
            align-items: center; gap: 8px; font-size: 0.9rem;
        }
        .btn-back:hover { background: #1E293B; transform: translateY(-1px); color: white; }
    </style>
</head>
<body>
    <?php include BASE_PATH . 'config/loader.php'; ?>
    <div class="main-card">
        <div class="icon-box"><i class="bi bi-lock-fill"></i></div>
        <h2>Akses Ditutup</h2>
        <p><?= $pesanTutup ?></p>
        <a href="<?= BASE_URL ?>" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>
    <script src="<?= BASE_URL ?>/assets/loader.js"></script>
</body>
</html>
