<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Dinas Kearsipan dan Perpustakaan</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/govtech.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/loader.css">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <?php include BASE_PATH . 'config/loader.php'; ?>

    <div class="card-clean p-4 p-md-5 shadow-lg" style="max-width: 450px; width: 100%; background: #fff;">
        <div class="text-center mb-4">
            <div class="d-flex justify-content-center gap-3 mb-4">
                <img src="<?= BASE_URL ?>/assets/logo_lobar.png" alt="Lobar" style="height: 60px;">
                <img src="<?= BASE_URL ?>/assets/logo_disarpus.png" alt="Disarpus" style="height: 60px;">
            </div>
            <h4 class="fw-extrabold mb-1 text-dark">LUPA PASSWORD</h4>
            <p class="text-muted small mb-0">Masukkan email admin Anda untuk menerima link reset.</p>
        </div>

        <?php if(!empty($pesan)): ?>
            <div class="alert alert-info border-0 bg-info-subtle text-info small mb-4 rounded-3 text-center">
                <i class="bi bi-info-circle-fill me-1"></i> <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">EMAIL ADMIN</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="nama@email.com" required autofocus>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-pill shadow-sm mb-4">
                KIRIM LINK RESET <i class="bi bi-send ms-2"></i>
            </button>

            <div class="text-center border-top pt-3">
                <a href="<?= BASE_URL ?>/auth/login" class="text-decoration-none small text-muted d-inline-flex align-items-center hover-scale">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
                </a>
            </div>
        </form>
    </div>

    <script src="<?= BASE_URL ?>/assets/loader.js"></script>
</body>
</html>
