
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Royal GovTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/govtech.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/admin-readability.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/admin-responsive.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/loader.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>

    </style>
</head>
<body>
    <?php include BASE_PATH . 'config/loader.php'; ?>
    <div class="sidebar-backdrop" onclick="toggleSidebar(false)"></div>

    <!-- Sidebar -->
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
            <a href="<?= BASE_URL ?>/admin/dashboard" class="nav-link active">
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
            <?php if (($_SESSION['admin_role'] ?? '') === 'super'): ?>
            <a href="<?= BASE_URL ?>/admin/users" class="nav-link">
                <i class="bi bi-people-fill"></i>
                <span>Admin Users</span>
            </a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/auth/logout" class="nav-link text-danger mt-3">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-white shadow-sm d-lg-none" onclick="toggleSidebar(true)">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h2 class="fw-bold mb-0 text-dark">Dashboard Overview</h2>
                    <p class="text-muted mb-0">Pantau statistik dan kelola akses survey.</p>
                </div>
            </div>
            <div class="bg-white px-3 py-2 rounded-pill shadow-sm border d-flex align-items-center gap-2">
                <i class="bi bi-calendar-day text-primary"></i>
                <span class="fw-bold text-dark small"><?= date('l, d M Y') ?></span>
            </div>
        </div>

        <!-- Kontrol Akses Kuesioner (Compact Design) -->
        <div class="row g-4 mb-5">
            <?php foreach([
                ['key' => 'IPLM', 'info' => $infoIPLM, 'status_key' => 'status_iplm', 'subtitle' => 'Literasi Masyarakat'],
                ['key' => 'TKM',  'info' => $infoTKM,  'status_key' => 'status_tkm',  'subtitle' => 'Kegemaran Membaca']
            ] as $sess): 
                $isOpen = $sess['info']['open'];
            ?>
            <div class="col-md-6">
                <div class="card-clean p-3 px-4 d-flex align-items-center justify-content-between gap-3 h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                             style="width: 50px; height: 50px; background-color: <?= $isOpen ? '#dcfce7' : '#fee2e2' ?>; color: <?= $isOpen ? '#166534' : '#991b1b' ?>;">
                            <i class="bi <?= $isOpen ? 'bi-broadcast' : 'bi-lock-fill' ?> fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0"><?= $sess['key'] ?></h6>
                            <div class="small fw-bold <?= $isOpen ? 'text-success' : 'text-danger' ?>">
                                <?= $isOpen ? 'Sesi Dibuka' : 'Sesi Ditutup' ?>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <?php if($sess['info']['mode'] == 'manual'): ?>
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <input type="hidden" name="aksi_status" value="toggle">
                            <input type="hidden" name="kunci" value="<?= $sess['status_key'] ?>">
                            <input type="hidden" name="status_baru" value="<?= $isOpen ? 'tutup' : 'buka' ?>">
                            <button class="btn <?= $isOpen ? 'btn-danger' : 'btn-success' ?> rounded-pill px-4 btn-sm fw-bold shadow-sm" style="min-width: 100px;">
                                <?= $isOpen ? 'Matikan' : 'Aktifkan' ?>
                            </button>
                        </form>
                        <?php else: ?>
                        <span class="badge bg-secondary rounded-pill">Otomatis</span>
                        <?php endif; ?>
                        
                        <button class="btn btn-light btn-sm rounded-circle text-muted" data-bs-toggle="modal" data-bs-target="#modalJadwal<?= $sess['key'] ?>" title="Jadwal Otomatis">
                            <i class="bi bi-gear-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Charts & Stats -->
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="card-clean p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">Statistik Partisipasi</h5>
                            <div class="d-flex align-items-center gap-2 text-muted small">
                                <i class="bi bi-bar-chart-fill text-primary"></i>
                                <span>Total <?= number_format($total_responden_tahunan) ?> Responden di Tahun <?= $tahun_chart ?></span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <form method="POST" id="formChartYear">
                                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                <input type="hidden" name="filter_chart" value="1">
                                <select name="tahun_chart" class="form-select border-secondary fw-bold bg-white shadow-sm" style="width: auto; min-width: 150px;" onchange="this.form.submit()">
                                    <?php 
                                    $thn_skrg = date('Y');
                                    for($t = $thn_skrg; $t >= $thn_skrg-3; $t--): ?>
                                        <option value="<?= $t ?>" <?= ($t == $tahun_chart) ? 'selected' : '' ?>>Tahun <?= $t ?></option>
                                    <?php endfor; ?>
                                </select>
                            </form>
                        </div>
                    </div>
                    
                    <div style="height: 350px; width: 100%;">
                        <canvas id="myChart"></canvas>
                    </div>

                </div>
            </div>
        </div>

        <!-- Demografi TKM Charts (Usia & Gender) -->
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card-clean p-4 shadow-sm">
                    <h5 class="fw-bold mb-1 text-dark">Demografi Usia (TKM)</h5>
                    <p class="text-muted small mb-4">Distribusi usia responden kuesioner TKM pada rentang waktu terpilih.</p>
                    <div style="height: 300px; width: 100%;">
                        <canvas id="tkmUsiaChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-clean p-4 shadow-sm">
                    <h5 class="fw-bold mb-1 text-dark">Demografi Jenis Kelamin (TKM)</h5>
                    <p class="text-muted small mb-4">Distribusi jenis kelamin responden kuesioner TKM pada rentang waktu terpilih.</p>
                    <div style="height: 300px; width: 100%;">
                        <canvas id="tkmGenderChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Range Filter Panel -->
        <div class="card-clean p-4 mb-5 shadow-sm">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Analisis Rentang Waktu</h6>
                    <span class="badge bg-white text-dark border shadow-sm px-3 py-2">
                        <i class="bi bi-calendar-week me-2 text-primary"></i><?= $range_label ?>
                    </span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end">
                        <div class="small text-muted fw-bold">TOTAL DATA</div>
                        <div class="h3 fw-bold mb-0 text-primary"><?= number_format($total_responden_range) ?></div>
                    </div>
                    <div class="vr mx-2"></div>
                    <div class="d-flex gap-3">
                        <div class="text-center">
                            <div class="small text-muted fw-bold">IPLM</div>
                            <div class="h5 fw-bold mb-0"><?= number_format($total_range_iplm) ?></div>
                        </div>
                        <div class="text-center">
                            <div class="small text-muted fw-bold">TKM</div>
                            <div class="h5 fw-bold mb-0"><?= number_format($total_range_tkm) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" class="row g-3 align-items-end">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <input type="hidden" name="filter_range" value="1">
                
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">DARI BULAN</label>
                    <select name="range_start_bulan" class="form-select bg-white">
                        <?php foreach($list_bulan as $k => $v): ?>
                            <option value="<?= $k ?>" <?= ($k == $range_start_bulan) ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">TAHUN</label>
                    <select name="range_start_tahun" class="form-select bg-white">
                        <?php for($t = date('Y'); $t >= date('Y')-2; $t--): ?>
                            <option value="<?= $t ?>" <?= ($t == $range_start_tahun) ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-1 text-center py-2"><i class="bi bi-arrow-right text-muted"></i></div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">SAMPAI BULAN</label>
                    <select name="range_end_bulan" class="form-select bg-white">
                        <?php foreach($list_bulan as $k => $v): ?>
                            <option value="<?= $k ?>" <?= ($k == $range_end_bulan) ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted">TAHUN</label>
                    <select name="range_end_tahun" class="form-select bg-white">
                        <?php for($t = date('Y'); $t >= date('Y')-2; $t--): ?>
                            <option value="<?= $t ?>" <?= ($t == $range_end_tahun) ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                        <i class="bi bi-funnel-fill me-2"></i>Filter Data
                    </button>
                </div>
            </form>
        </div>

    </main>

    <!-- Modals for Scheduling -->
    <?php foreach(['IPLM', 'TKM'] as $j): $low = strtolower($j); ?>
    <div class="modal fade" id="modalJadwal<?= $j ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Pengaturan Jadwal <?= $j ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    <div class="modal-body p-4">
                        <input type="hidden" name="aksi_status" value="save_schedule">
                        <input type="hidden" name="jenis" value="<?= $low ?>">
                        
                        <div class="alert alert-light border mb-4">
                            <div class="d-flex gap-3">
                                <i class="bi bi-info-circle-fill text-primary fs-4"></i>
                                <small class="text-muted">Fitur ini memungkinkan sistem membuka/tutup akses kuesioner secara otomatis berdasarkan tanggal yang ditentukan.</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Mode Akses</label>
                            <select name="mode" class="form-select py-3 fw-bold" onchange="toggleDateInput('<?= $low ?>', this.value)">
                                <option value="manual" <?= ($settings[$low.'_mode']??'')=='manual'?'selected':'' ?>>Manual (Kontrol Penuh)</option>
                                <option value="auto" <?= ($settings[$low.'_mode']??'')=='auto'?'selected':'' ?>>Otomatis (Terjadwal)</option>
                            </select>
                        </div>
                        
                        <div id="date-inputs-<?= $low ?>" class="bg-light p-3 rounded-3 border" style="<?= ($settings[$low.'_mode']??'')=='manual'?'display:none':'' ?>">
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Waktu Mulai</label>
                                <input type="datetime-local" name="start_date" class="form-control" value="<?= !empty($settings[$low.'_start']) ? date('Y-m-d\TH:i', strtotime($settings[$low.'_start'])) : '' ?>">
                            </div>
                            <div class="mb-0">
                                <label class="form-label small fw-bold">Waktu Selesai</label>
                                <input type="datetime-local" name="end_date" class="form-control" value="<?= !empty($settings[$low.'_end']) ? date('Y-m-d\TH:i', strtotime($settings[$low.'_end'])) : '' ?>">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 px-4 pb-4">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar(open) {
            document.body.classList.toggle('sidebar-open', open);
        }

        document.querySelectorAll('.sidebar .nav-link').forEach((link) => {
            link.addEventListener('click', () => toggleSidebar(false));
        });

        function toggleDateInput(jenis, val) {
            document.getElementById('date-inputs-' + jenis).style.display = (val === 'auto') ? 'block' : 'none';
        }

        const ctx = document.getElementById('myChart');
        // Custom Fonts for Chart
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#64748b';
        
        const gradientIplm = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        gradientIplm.addColorStop(0, 'rgba(15, 82, 186, 0.2)'); 
        gradientIplm.addColorStop(1, 'rgba(15, 82, 186, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($chart_labels) ?>,
                datasets: [
                    {
                        label: 'IPLM',
                        data: <?= json_encode($data_iplm_chart) ?>, 
                        borderWidth: 3, 
                        borderColor: '#0F52BA', 
                        backgroundColor: gradientIplm, 
                        pointBackgroundColor: '#0F52BA', 
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5, 
                        pointHoverRadius: 8,
                        pointStyle: 'circle',
                        tension: 0.4, 
                        fill: true,
                        order: 2
                    },
                    {
                        label: 'TKM',
                        data: <?= json_encode($data_tkm_chart) ?>, 
                        borderWidth: 3, 
                        borderColor: '#F4C430', 
                        backgroundColor: 'transparent', 
                        pointBackgroundColor: '#F4C430', 
                        pointBorderColor: '#fff', 
                        pointBorderWidth: 2, 
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointStyle: 'rectRot',
                        tension: 0.4, 
                        borderDash: [6, 4],
                        order: 1
                    }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: { 
                    y: { 
                        beginAtZero: true, 
                        ticks: { stepSize: 1, padding: 10 },
                        grid: { color: '#f1f5f9', drawBorder: false }
                    }, 
                    x: { 
                        grid: { display: false },
                        ticks: { padding: 10 }
                    } 
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: { usePointStyle: true, boxWidth: 8, padding: 20, font: { weight: 600 } }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 13 },
                        bodyFont: { size: 13 },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true
                    }
                }
            }
        });

        // TKM Usia Chart
        const ctxUsia = document.getElementById('tkmUsiaChart');
        const usiaData = <?= json_encode($tkm_usia_stats) ?>;
        const usiaLabels = Object.keys(usiaData);
        const usiaValues = Object.values(usiaData);

        new Chart(ctxUsia, {
            type: 'bar',
            data: {
                labels: usiaLabels,
                datasets: [{
                    label: 'Jumlah Responden',
                    data: usiaValues,
                    backgroundColor: '#0F52BA',
                    borderRadius: 6,
                    borderWidth: 0,
                    barThickness: 24
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, padding: 10 },
                        grid: { color: '#f1f5f9', drawBorder: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { padding: 10 }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
                        cornerRadius: 8
                    }
                }
            }
        });

        // TKM Gender Chart
        const ctxGender = document.getElementById('tkmGenderChart');
        const genderData = <?= json_encode($tkm_gender_stats) ?>;
        const genderLabels = Object.keys(genderData);
        const genderValues = Object.values(genderData);

        new Chart(ctxGender, {
            type: 'doughnut',
            data: {
                labels: genderLabels,
                datasets: [{
                    data: genderValues,
                    backgroundColor: ['#0F52BA', '#F472B6'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, boxWidth: 8, padding: 20, font: { weight: 600 } }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
                        cornerRadius: 8
                    }
                }
            }
        });
    </script>
    <script src="<?= BASE_URL ?>/assets/loader.js"></script>
</body>
</html>

