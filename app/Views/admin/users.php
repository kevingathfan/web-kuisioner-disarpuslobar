<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Admin - DISARPUS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/loader.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/admin-responsive.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/govtech.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/admin-readability.css">
</head>
<body>
    <?php include BASE_PATH . 'config/loader.php'; ?>
    <div class="sidebar-backdrop" onclick="toggleSidebar(false)"></div>

    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <h6 class="mb-0 fw-bold">ADMIN PANEL</h6>
            </div>
            <button class="btn btn-sm btn-light d-lg-none" onclick="toggleSidebar(false)">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="nav flex-column gap-1">
            <div class="sidebar-label">Utama</div>
            <a href="<?= BASE_URL ?>/admin/dashboard" class="nav-link">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/perpustakaan" class="nav-link">
                <i class="bi bi-building"></i>
                <span>Perpustakaan</span>
            </a>
            
            <div class="sidebar-label mt-3">Pelaporan</div>
            <a href="<?= BASE_URL ?>/admin/hasil_kuisioner" class="nav-link">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Hasil Kuesioner</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/atur_pertanyaan" class="nav-link">
                <i class="bi bi-gear-wide-connected"></i>
                <span>Atur Pertanyaan</span>
            </a>
            <a href="<?= BASE_URL ?>/admin/pengaduan" class="nav-link">
                <i class="bi bi-chat-left-text-fill"></i>
                <span>Pengaduan</span>
            </a>

            <div class="sidebar-label mt-3">Sistem</div>
            <a href="<?= BASE_URL ?>/admin/users" class="nav-link active">
                <i class="bi bi-people-fill"></i>
                <span>Admin Users</span>
            </a>
            <a href="<?= BASE_URL ?>/auth/logout" class="nav-link text-danger mt-3">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar</span>
            </a>
        </div>
    </nav>

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 page-header">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-dark btn-sm d-lg-none" onclick="toggleSidebar(true)"><i class="bi bi-list"></i></button>
                <div>
                    <h2 class="fw-bold m-0 page-title">Kelola Admin</h2>
                    <p class="text-muted m-0 page-subtitle">Manajemen akun dan log aktivitas sistem.</p>
                </div>
            </div>
        </div>

        <?php if ($pesan): ?>
            <div class="alert alert-<?= $tipe ?> alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-<?= $tipe === 'success' ? 'check-circle' : 'exclamation-circle' ?>-fill me-2"></i>
                <?= $pesan ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Left Column: Add New Admin -->
            <div class="col-lg-4">
                <div class="card-clean h-100">
                    <div class="p-4 border-bottom bg-light bg-opacity-50">
                        <div class="d-flex align-items-center gap-2 text-primary">
                            <i class="bi bi-person-plus-fill fs-5"></i>
                            <h6 class="fw-bold mb-0">Tambah Admin Baru</h6>
                        </div>
                    </div>
                    <div class="p-4">
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">NAMA LENGKAP</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nama" class="form-control border-start-0 ps-0" placeholder="Contoh: Admin Perpustakaan" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">EMAIL (OPSIONAL)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="admin@example.com">
                                </div>
                                <div class="form-text small">Diperlukan untuk fitur reset password.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">ROLE / HAK AKSES</label>
                                <select name="role" class="form-select" required>
                                    <option value="admin">Admin (Hanya Kuesioner & Laporan)</option>
                                    <option value="super">Super Admin (Akses Penuh)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">PASSWORD</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-key"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="******" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Akun
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: User List -->
            <div class="col-lg-8">
                <div class="card-clean h-100">
                    <div class="p-4 border-bottom bg-light bg-opacity-50 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2 text-dark">
                            <i class="bi bi-people-fill fs-5"></i>
                            <h6 class="fw-bold mb-0">Daftar Admin Aktif</h6>
                        </div>
                        <span class="badge bg-primary rounded-pill"><?= count($users) ?> Akun</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3" width="50">#</th>
                                    <th class="py-3">Admin</th>
                                    <th class="py-3">Role</th>
                                    <th class="py-3">Dibuat Pada</th>
                                    <th class="pe-4 py-3 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)): ?>
                                    <tr><td colspan="4" class="text-center text-muted py-5">Belum ada data admin.</td></tr>
                                <?php else: ?>
                                    <?php $no=1; foreach ($users as $u): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-muted"><?= $no++ ?></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.1rem;">
                                                        <?= strtoupper(substr($u['nama'], 0, 1)) ?>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark"><?= htmlspecialchars($u['nama']) ?></div>
                                                        <div class="small text-muted"><?= htmlspecialchars($u['email'] ?? '-') ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if (($u['role'] ?? 'admin') === 'super'): ?>
                                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Super Admin</span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">Admin</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-muted small">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                <?= !empty($u['created_at']) ? date('d M Y', strtotime($u['created_at'])) : '-' ?>
                                                <div class="text-xs text-muted ms-3"><?= !empty($u['created_at']) ? date('H:i', strtotime($u['created_at'])) : '' ?></div>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <?php if ((int)($u['is_primary'] ?? 0) === 0 && (int)$u['id'] !== (int)$_SESSION['admin_id']): ?>
                                                        <button class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="confirmHapus(<?= (int)$u['id'] ?>)">
                                                            <i class="bi bi-trash3-fill me-1"></i> Hapus
                                                        </button>
                                                    <?php elseif ((int)($u['is_primary'] ?? 0) === 1): ?>
                                                        <span class="badge bg-dark text-white border border-dark px-2 py-1"><i class="bi bi-star-fill me-1"></i> Admin Utama</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">Anda</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Full Width: System Logs with Tabs -->
            <div class="col-12">
                <div class="card-clean">
                    <div class="card-header bg-transparent border-bottom px-4 pt-4 pb-0">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active fw-bold text-dark" data-bs-toggle="tab" data-bs-target="#tab-reset-logs">
                                    <i class="bi bi-shield-lock me-2"></i>Log Reset Password
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-bold text-dark" data-bs-toggle="tab" data-bs-target="#tab-email-logs">
                                    <i class="bi bi-envelope me-2"></i>Log Pengiriman Email
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content">
                            <!-- Reset Logs Tab -->
                            <div class="tab-pane fade show active" id="tab-reset-logs">
                                <div class="d-flex justify-content-between align-items-center p-3 border-bottom bg-light bg-opacity-25">
                                    <small class="text-muted fst-italic">Menampilkan 20 riwayat permintaan reset password terakhir.</small>
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmHapusLog()">
                                        <i class="bi bi-trash me-1"></i> Bersihkan Log
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4">User</th>
                                                <th>IP Address</th>
                                                <th>Waktu Request</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($reset_logs)): ?>
                                                <tr><td colspan="3" class="text-center text-muted py-4">Belum ada log aktivitas.</td></tr>
                                            <?php else: ?>
                                                <?php foreach ($reset_logs as $log): ?>
                                                    <tr>
                                                        <td class="ps-4">
                                                            <span class="fw-bold text-dark"><?= htmlspecialchars($log['nama']) ?></span>
                                                        </td>
                                                        <td class="font-monospace text-muted small"><?= htmlspecialchars($log['ip_address'] ?? '-') ?></td>
                                                        <td class="small"><?= date('d M Y H:i:s', strtotime($log['created_at'])) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Email Logs Tab -->
                            <div class="tab-pane fade" id="tab-email-logs">
                                <div class="d-flex justify-content-between align-items-center p-3 border-bottom bg-light bg-opacity-25">
                                    <small class="text-muted fst-italic">Menampilkan 20 riwayat pengiriman email terakhir.</small>
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmHapusEmailLog()">
                                        <i class="bi bi-trash me-1"></i> Bersihkan Log
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4">Email Penerima</th>
                                                <th>Status Pengiriman</th>
                                                <th>Pesan Sistem</th>
                                                <th>Waktu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($email_logs)): ?>
                                                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada log email.</td></tr>
                                            <?php else: ?>
                                                <?php foreach ($email_logs as $log): ?>
                                                    <tr>
                                                        <td class="ps-4 font-monospace small"><?= htmlspecialchars($log['email']) ?></td>
                                                        <td>
                                                            <?php if(strtolower($log['status']) == 'sent'): ?>
                                                                <span class="badge bg-success-subtle text-success border border-success-subtle">TERKIRIM</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">GAGAL</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="small text-muted text-break" style="max-width: 300px;"><?= htmlspecialchars($log['error_message'] ?? '-') ?></td>
                                                        <td class="small"><?= date('d M Y H:i:s', strtotime($log['created_at'])) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleSidebar(open) {
            document.body.classList.toggle('sidebar-open', open);
        }
        document.querySelectorAll('.sidebar .nav-link').forEach((link) => {
            link.addEventListener('click', () => toggleSidebar(false));
        });

        function confirmHapus(id) {
            Swal.fire({
                title: 'Hapus akun admin?',
                text: 'Akun akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.innerHTML = `
                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                        <input type="hidden" name="aksi" value="hapus">
                        <input type="hidden" name="id" value="${id}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function confirmHapusLog() {
            Swal.fire({
                title: 'Hapus semua log?',
                text: 'Log reset password akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('csrf_token', '<?= csrf_token() ?>');
                    formData.append('aksi', 'hapus_log');
                    formData.append('ajax', '1');

                    fetch('<?= BASE_URL ?>/admin/users', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Berhasil!', data.message, 'success');
                            const tbody = document.querySelector('#tab-reset-logs tbody');
                            if (tbody) {
                                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-4">Belum ada log aktivitas.</td></tr>';
                            }
                        } else {
                            Swal.fire('Gagal!', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                    });
                }
            });
        }

        function confirmHapusEmailLog() {
            Swal.fire({
                title: 'Hapus log email?',
                text: 'Riwayat pengiriman email akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('csrf_token', '<?= csrf_token() ?>');
                    formData.append('aksi', 'hapus_email_log');
                    formData.append('ajax', '1');

                    fetch('<?= BASE_URL ?>/admin/users', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Berhasil!', data.message, 'success');
                            const tbody = document.querySelector('#tab-email-logs tbody');
                            if (tbody) {
                                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">Belum ada log email.</td></tr>';
                            }
                        } else {
                            Swal.fire('Gagal!', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                    });
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/loader.js"></script>
</body>
</html>
