<?php
// Section icon mapping
$section_icons_map = [
    'data demografi' => 'bi-person-badge',
    'demografi' => 'bi-person-badge',
    'data perpustakaan' => 'bi-building',
    'perpustakaan' => 'bi-building',
    'koleksi' => 'bi-collection',
    'layanan' => 'bi-hand-thumbs-up',
    'sdm' => 'bi-people',
    'sarana' => 'bi-tools',
    'prasarana' => 'bi-tools',
    'anggaran' => 'bi-cash-stack',
    'teknologi' => 'bi-cpu',
    'kebiasaan membaca' => 'bi-book',
    'pra membaca' => 'bi-search',
    'saat membaca' => 'bi-journal-text',
    'pasca membaca' => 'bi-check2-square',
    'interaksi' => 'bi-building',
];

function get_sidebar_icon($name, $icons) {
    $lower = strtolower(trim($name));
    foreach ($icons as $key => $icon) {
        if (strpos($lower, $key) !== false) return $icon;
    }
    return 'bi-bookmark-check-fill';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survei IPLM — Disarpus Lombok Barat</title>
    <meta name="description" content="Survei Indeks Pembangunan Literasi Masyarakat (IPLM) — Kabupaten Lombok Barat.">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/loader.css">
    <style>
        /* ===== Base Reset ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; outline: none; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F8FAFC;
            color: #0F172A;
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }
        a { text-decoration: none; color: inherit; }

        /* ===== Navbar ===== */
        .sv-navbar {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid #E2E8F0;
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 0.75rem 0;
        }
        .sv-navbar .wrapper {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sv-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sv-brand-logos {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .sv-brand-logos img {
            height: 34px;
            width: auto;
        }
        .sv-brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }
        .sv-brand-title {
            font-weight: 800;
            font-size: 1.05rem;
            color: #2563EB;
            letter-spacing: -0.3px;
        }
        .sv-brand-subtitle {
            font-size: 0.72rem;
            color: #64748B;
            font-weight: 500;
        }
        .sv-nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sv-nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }
        .sv-nav-btn-outline {
            background: transparent;
            border: 1.5px solid #E2E8F0;
            color: #0F172A;
        }
        .sv-nav-btn-outline:hover {
            border-color: #2563EB;
            color: #2563EB;
            background: #EFF6FF;
        }
        .sv-nav-btn-primary {
            background: #2563EB;
            color: #fff;
        }
        .sv-nav-btn-primary:hover {
            background: #1E40AF;
        }

        /* ===== Hero Section ===== */
        .sv-hero {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 24px 0;
        }
        .sv-hero-card {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 20px;
            padding: 2rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .sv-hero-info h1 {
            font-size: 1.65rem;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.8px;
            margin-bottom: 0.4rem;
            line-height: 1.25;
        }
        .sv-hero-info .sv-hero-sub {
            color: #64748B;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
            line-height: 1.5;
        }
        .sv-hero-badges {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .sv-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 600;
            color: #475569;
        }
        .sv-badge i { color: #2563EB; font-size: 0.85rem; }
        .sv-badge strong { color: #0F172A; }

        /* Progress Card */
        .sv-progress-card {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-shrink: 0;
        }
        .sv-progress-details { min-width: 200px; }
        .sv-progress-label {
            font-size: 0.78rem;
            color: #64748B;
            font-weight: 600;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .sv-progress-pct {
            font-size: 2.25rem;
            font-weight: 800;
            color: #2563EB;
            line-height: 1;
            margin-bottom: 10px;
        }
        .sv-progress-bar-track {
            width: 100%;
            height: 8px;
            background: #E2E8F0;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 8px;
        }
        .sv-progress-bar-fill {
            height: 100%;
            background: #2563EB;
            border-radius: 4px;
            width: 0%;
            transition: width 0.4s ease;
        }
        .sv-progress-text {
            font-size: 0.78rem;
            color: #64748B;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .sv-progress-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: #EFF6FF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #2563EB;
            flex-shrink: 0;
        }

        /* ===== Two Column Layout ===== */
        .sv-layout {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1.5rem 24px 3rem;
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 1.5rem;
            align-items: start;
        }

        /* ===== Sidebar ===== */
        .sv-sidebar {
            position: sticky;
            top: 80px;
            max-height: calc(100vh - 100px);
            overflow-y: auto;
        }
        .sv-sidebar::-webkit-scrollbar { width: 0; }
        .sv-sidebar-card {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            margin-bottom: 1rem;
        }
        .sv-sidebar-title {
            font-size: 0.78rem;
            font-weight: 700;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .sv-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-bottom: 4px;
            border: 1.5px solid transparent;
        }
        .sv-nav-item:hover { background: #F8FAFC; }
        .sv-nav-item.active {
            background: #EFF6FF;
            border-color: #BFDBFE;
        }
        .sv-nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #F1F5F9;
            color: #64748B;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }
        .sv-nav-item.active .sv-nav-icon {
            background: #2563EB;
            color: #fff;
        }
        .sv-nav-info { flex: 1; min-width: 0; }
        .sv-nav-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: #0F172A;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sv-nav-progress-text {
            font-size: 0.7rem;
            color: #94A3B8;
            font-weight: 500;
        }
        .sv-nav-progress-bar {
            width: 100%;
            height: 3px;
            background: #E2E8F0;
            border-radius: 2px;
            margin-top: 4px;
            overflow: hidden;
        }
        .sv-nav-progress-fill {
            height: 100%;
            background: #2563EB;
            border-radius: 2px;
            width: 0%;
            transition: width 0.3s ease;
        }
        .sv-nav-item.completed .sv-nav-progress-fill { background: #10B981; }
        .sv-nav-item.completed .sv-nav-icon { background: #ECFDF5; color: #10B981; }

        .sv-legend {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 0.75rem;
        }
        .sv-legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.75rem;
            color: #64748B;
            font-weight: 500;
        }
        .sv-legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .sv-legend-dot.current { background: #2563EB; }
        .sv-legend-dot.done { background: #10B981; }
        .sv-legend-dot.pending { background: #E2E8F0; }

        .sv-help-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 1.25rem;
        }
        .sv-help-box h4 {
            font-size: 0.85rem;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 6px;
        }
        .sv-help-box p {
            font-size: 0.78rem;
            color: #64748B;
            line-height: 1.5;
            margin-bottom: 12px;
        }
        .sv-help-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 50px;
            background: #fff;
            border: 1.5px solid #2563EB;
            color: #2563EB;
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .sv-help-btn:hover { background: #EFF6FF; }

        .sv-main { min-width: 0; }

        /* ===== Mobile Sidebar Toggle ===== */
        .sv-sidebar-toggle {
            display: none;
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 999;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #2563EB;
            color: #fff;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(37,99,235,0.35);
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }
        .sv-sidebar-toggle:hover { background: #1E40AF; transform: scale(1.05); }
        .sv-sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 998;
        }

        @media (max-width: 1024px) {
            .sv-layout { grid-template-columns: 1fr; }
            .sv-sidebar {
                position: fixed;
                top: 0; left: 0;
                width: 280px;
                height: 100dvh;
                max-height: 100dvh;
                z-index: 1001;
                background: #fff;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                padding: 1.5rem;
                overflow-y: auto;
                border-right: 1px solid #E2E8F0;
                box-shadow: 4px 0 12px rgba(0,0,0,0.08);
            }
            .sv-sidebar.open { transform: translateX(0); }
            .sv-sidebar-toggle { display: flex; }
            body.sidebar-open .sv-sidebar-backdrop { display: block; }
        }

        @media (max-width: 768px) {
            .sv-hero-card {
                flex-direction: column;
                align-items: flex-start;
                padding: 1.5rem;
                gap: 1.5rem;
            }
            .sv-progress-card { width: 100%; }
            .sv-progress-details { flex: 1; min-width: 0; }
            .sv-hero-info h1 { font-size: 1.35rem; }
            .sv-navbar .wrapper { padding: 0 16px; }
            .sv-hero { padding: 1.25rem 16px 0; }
            .sv-layout { padding: 1rem 16px 2rem; }
        }

        @media (max-width: 480px) {
            .sv-hero-info h1 { font-size: 1.15rem; }
            .sv-hero-badges { flex-direction: column; gap: 8px; }
            .sv-progress-pct { font-size: 1.75rem; }
            .sv-progress-icon { width: 48px; height: 48px; font-size: 1.3rem; }
            .sv-nav-btn span { display: none; }
        }
    </style>
</head>
<body>
    <?php include BASE_PATH . 'config/loader.php'; ?>

    <!-- Navbar -->
    <nav class="sv-navbar">
        <div class="wrapper">
            <a href="<?= BASE_URL ?>" class="sv-brand">
                <div class="sv-brand-logos">
                    <img src="<?= BASE_URL ?>/assets/logo_lobar.png" alt="Logo Lobar">
                    <img src="<?= BASE_URL ?>/assets/logo_disarpus.png" alt="Logo Disarpus">
                </div>
                <div class="sv-brand-text">
                    <span class="sv-brand-title">DISARPUS</span>
                    <span class="sv-brand-subtitle">Kab. Lombok Barat</span>
                </div>
            </a>
            <div class="sv-nav-actions">
                <a href="<?= BASE_URL ?>#footer-map" class="sv-nav-btn sv-nav-btn-outline">
                    <i class="bi bi-geo-alt-fill"></i> <span>Kunjungi Kami</span>
                </a>
                <a href="<?= BASE_URL ?>/pustakawan/form_pengaduan" class="sv-nav-btn sv-nav-btn-primary">
                    <i class="bi bi-chat-quote-fill"></i> <span>Layanan Pengaduan</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="sv-hero">
        <div class="sv-hero-card">
            <div class="sv-hero-info">
                <h1>Survei Indeks Pembangunan Literasi Masyarakat (IPLM)</h1>
                <p class="sv-hero-sub">Instrumen strategis literasi<br>Kabupaten Lombok Barat <?= date('Y') ?></p>
                <div class="sv-hero-badges">
                    <span class="sv-badge">
                        <i class="bi bi-calendar3"></i> Tahun Survei <strong><?= date('Y') ?></strong>
                    </span>
                    <span class="sv-badge">
                        <i class="bi bi-list-check"></i> Jumlah Pertanyaan <strong><?= $total_questions ?></strong>
                    </span>
                </div>
            </div>
            <div class="sv-progress-card">
                <div class="sv-progress-details">
                    <div class="sv-progress-label">Progres Pengisian</div>
                    <div class="sv-progress-pct" id="heroProgressPct">0%</div>
                    <div class="sv-progress-bar-track">
                        <div class="sv-progress-bar-fill" id="heroProgressBar"></div>
                    </div>
                    <div class="sv-progress-text">
                        <i class="bi bi-check-circle-fill" style="color:#10B981;"></i>
                        <span id="heroProgressText">0 dari <?= $total_questions ?> pertanyaan terjawab</span>
                    </div>
                </div>
                <div class="sv-progress-icon">
                    <i class="bi bi-clipboard-check"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Two Column Layout -->
    <div class="sv-layout">
        <!-- Sidebar -->
        <aside class="sv-sidebar" id="survSidebar">
            <div class="sv-sidebar-card">
                <div class="sv-sidebar-title">
                    <i class="bi bi-compass"></i> Navigasi Survei
                </div>
                <div class="sv-sidebar-title" style="font-size:0.72rem; margin-bottom:0.75rem; color:#94A3B8;">
                    Ringkasan Progres
                </div>
                <?php foreach ($sections as $idx => $sec): ?>
                    <div class="sv-nav-item <?= $idx === 0 ? 'active' : '' ?>" 
                         data-sidebar-index="<?= $idx ?>" 
                         onclick="window.jumpToSection(<?= $idx ?>)">
                        <div class="sv-nav-icon">
                            <i class="bi <?= get_sidebar_icon($sec['kategori_bagian'], $section_icons_map) ?>"></i>
                        </div>
                        <div class="sv-nav-info">
                            <div class="sv-nav-name"><?= htmlspecialchars($sec['kategori_bagian']) ?></div>
                            <div class="sv-nav-progress-text" data-sidebar-progress="<?= $idx ?>">0 / <?= $sec['total_questions'] ?></div>
                            <div class="sv-nav-progress-bar">
                                <div class="sv-nav-progress-fill" data-sidebar-bar="<?= $idx ?>"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="sv-legend">
                    <div class="sv-legend-item">
                        <span class="sv-legend-dot current"></span> Sedang Dikerjakan
                    </div>
                    <div class="sv-legend-item">
                        <span class="sv-legend-dot done"></span> Selesai
                    </div>
                    <div class="sv-legend-item">
                        <span class="sv-legend-dot pending"></span> Belum Dikerjakan
                    </div>
                </div>
            </div>

            <div class="sv-sidebar-card">
                <div class="sv-help-box">
                    <h4>Butuh Bantuan?</h4>
                    <p>Hubungi kami jika mengalami kesulitan saat mengisi survei.</p>
                    <a href="<?= BASE_URL ?>/pustakawan/form_pengaduan" class="sv-help-btn">
                        <i class="bi bi-headset"></i> Hubungi Kami
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="sv-main">
            <?php 
            require_once __DIR__ . '/render_kuesioner.php';
            global $pdo;
            render_dynamic_form($pdo, 'IPLM', $library_id, $auto_isi); 
            ?>
        </main>
    </div>

    <!-- Mobile sidebar toggle -->
    <button class="sv-sidebar-toggle" id="sidebarToggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>
    <div class="sv-sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

    <script src="<?= BASE_URL ?>/assets/loader.js"></script>
    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('survSidebar');
        sidebar.classList.toggle('open');
        document.body.classList.toggle('sidebar-open');
    }

    window.updateSidebarActive = function(activeIndex) {
        document.querySelectorAll('.sv-nav-item').forEach(item => {
            const idx = parseInt(item.getAttribute('data-sidebar-index'));
            item.classList.toggle('active', idx === activeIndex);
        });
        const sidebar = document.getElementById('survSidebar');
        if (sidebar.classList.contains('open')) {
            toggleSidebar();
        }
    };

    window.updateHeroProgress = function(progress) {
        document.getElementById('heroProgressPct').textContent = progress.percentage + '%';
        document.getElementById('heroProgressBar').style.width = progress.percentage + '%';
        document.getElementById('heroProgressText').textContent = 
            progress.filledCount + ' dari ' + progress.totalQuestions + ' pertanyaan terjawab';
    };

    window.updateSidebarProgress = function(progress) {
        Object.keys(progress.sections).forEach(idx => {
            const sec = progress.sections[idx];
            const progressText = document.querySelector('[data-sidebar-progress="' + idx + '"]');
            const progressBar = document.querySelector('[data-sidebar-bar="' + idx + '"]');
            const navItem = document.querySelector('[data-sidebar-index="' + idx + '"]');
            
            if (progressText) progressText.textContent = sec.filled + ' / ' + sec.total;
            if (progressBar) {
                const pct = sec.total > 0 ? (sec.filled / sec.total * 100) : 0;
                progressBar.style.width = pct + '%';
            }
            if (navItem) {
                navItem.classList.toggle('completed', sec.filled === sec.total && sec.total > 0);
            }
        });
    };
    </script>
</body>
</html>
