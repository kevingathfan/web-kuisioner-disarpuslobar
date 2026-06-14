<?php
// web-perpus-v1/pustakawan/render_kuesioner.php

function render_dynamic_form($pdo, $jenis_kuesioner, $library_id, $defaults = []) {
    require_once __DIR__ . '/../config/public_security.php';
    
    // 1. AMBIL DATA
    $stmt = $pdo->prepare("SELECT m.* FROM master_pertanyaan m "
        . "LEFT JOIN kategori_bagian kb ON kb.jenis_kuesioner = m.jenis_kuesioner AND kb.name = m.kategori_bagian "
        . "WHERE m.jenis_kuesioner = ? "
        . "ORDER BY COALESCE(kb.position, 9999) ASC, m.kategori_bagian ASC, CAST(m.urutan AS UNSIGNED) ASC, m.id ASC");
    $stmt->execute([$jenis_kuesioner]);
    $raw_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$raw_data) {
        echo '<div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-clipboard-x"></i></div>
                <h3>Data Kuesioner Belum Tersedia</h3>
                <p>Silakan hubungi administrator.</p>
              </div>';
        return;
    }

    // --- AUTO-FILL SETTING IPLM (BERDASARKAN ID PERTANYAAN) ---
    $auto_ids = ['jenis' => null, 'subjenis' => null, 'nama' => null];
    if ($jenis_kuesioner === 'IPLM') {
        try {
            $stmtAuto = $pdo->prepare("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('iplm_autofill_jenis_id','iplm_autofill_subjenis_id','iplm_autofill_nama_id')");
            $stmtAuto->execute();
            $autoRows = $stmtAuto->fetchAll(PDO::FETCH_KEY_PAIR);
            $auto_ids['jenis'] = !empty($autoRows['iplm_autofill_jenis_id']) ? (int)$autoRows['iplm_autofill_jenis_id'] : null;
            $auto_ids['subjenis'] = !empty($autoRows['iplm_autofill_subjenis_id']) ? (int)$autoRows['iplm_autofill_subjenis_id'] : null;
            $auto_ids['nama'] = !empty($autoRows['iplm_autofill_nama_id']) ? (int)$autoRows['iplm_autofill_nama_id'] : null;
        } catch (Exception $e) {}
    }

    // 2. GROUPING
    $pertanyaan = [];
    $kategori_order = [];
    foreach ($raw_data as $row) {
        $bagian = $row['kategori_bagian'];
        if (!isset($pertanyaan[$bagian])) {
            $pertanyaan[$bagian] = [];
            $kategori_order[] = $bagian;
        }
        $pertanyaan[$bagian][] = $row;
    }
    
    $pertanyaan_ordered = [];
    foreach ($kategori_order as $bagian) {
        $pertanyaan_ordered[$bagian] = $pertanyaan[$bagian];
    }

    $total_questions = count($raw_data);
    $total_sections = count($kategori_order);

    // --- Section icon mapping ---
    $section_icons = [
        'data demografi' => 'bi-person-badge',
        'demografi' => 'bi-person-badge',
        'kebiasaan membaca' => 'bi-book',
        'pra membaca' => 'bi-search',
        'saat membaca' => 'bi-journal-text',
        'pasca membaca' => 'bi-check2-square',
        'interaksi perpustakaan' => 'bi-building',
        'data perpustakaan' => 'bi-building',
        'koleksi' => 'bi-collection',
        'layanan' => 'bi-hand-thumbs-up',
        'sdm' => 'bi-people',
        'sarana prasarana' => 'bi-tools',
        'anggaran' => 'bi-cash-stack',
        'teknologi' => 'bi-cpu',
    ];

    function get_section_icon($name, $icons) {
        $lower = strtolower(trim($name));
        foreach ($icons as $key => $icon) {
            if (strpos($lower, $key) !== false) return $icon;
        }
        return 'bi-bookmark-check-fill';
    }

    // --- ASSETS ---
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">';

    // --- CSS MODERN GOVTECH DASHBOARD ---
    echo '
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap");

        :root {
            --sv-primary: #2563EB;
            --sv-primary-dark: #1E40AF;
            --sv-primary-light: #EFF6FF;
            --sv-primary-50: #DBEAFE;
            --sv-bg: #F8FAFC;
            --sv-card: #FFFFFF;
            --sv-border: #E2E8F0;
            --sv-border-light: #F1F5F9;
            --sv-text: #0F172A;
            --sv-text-secondary: #64748B;
            --sv-text-muted: #94A3B8;
            --sv-success: #10B981;
            --sv-success-light: #ECFDF5;
            --sv-danger: #EF4444;
            --sv-shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
            --sv-shadow-md: 0 4px 6px -1px rgba(0,0,0,0.06), 0 2px 4px -1px rgba(0,0,0,0.04);
            --sv-shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.06), 0 4px 6px -2px rgba(0,0,0,0.03);
            --sv-radius: 16px;
            --sv-radius-sm: 10px;
        }

        /* ===== Form Container ===== */
        .survey-form-wrapper {
            font-family: "Plus Jakarta Sans", sans-serif;
            color: var(--sv-text);
            font-size: 15px;
            line-height: 1.6;
        }

        /* ===== Empty State ===== */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--sv-card);
            border: 1px solid var(--sv-border);
            border-radius: var(--sv-radius);
        }
        .empty-state-icon {
            font-size: 3rem;
            color: var(--sv-text-muted);
            margin-bottom: 1rem;
        }
        .empty-state h3 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .empty-state p {
            color: var(--sv-text-secondary);
        }

        /* ===== Clear Form Button ===== */
        .form-toolbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1.25rem;
        }
        .btn-clear-form {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 50px;
            border: 1px solid var(--sv-border);
            background: var(--sv-card);
            color: var(--sv-text-secondary);
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }
        .btn-clear-form:hover {
            border-color: var(--sv-danger);
            color: var(--sv-danger);
            background: #FEF2F2;
        }

        /* ===== Section Card ===== */
        .section-card {
            background: var(--sv-card);
            border-radius: var(--sv-radius);
            box-shadow: var(--sv-shadow-sm);
            border: 1px solid var(--sv-border);
            margin-bottom: 1.5rem;
            overflow: hidden;
            display: none; /* Hidden by default, JS shows active */
        }
        .section-card.active {
            display: block;
        }

        /* Section Header */
        .section-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--sv-border);
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .section-header-icon {
            width: 42px;
            height: 42px;
            border-radius: var(--sv-radius-sm);
            background: var(--sv-primary-light);
            color: var(--sv-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }
        .section-header-text h2 {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0;
            color: var(--sv-text);
            letter-spacing: -0.3px;
        }
        .section-header-text p {
            font-size: 0.82rem;
            color: var(--sv-text-secondary);
            margin: 2px 0 0;
        }

        /* ===== Question Item ===== */
        .q-item {
            padding: 1.75rem 2rem;
            border-bottom: 1px solid var(--sv-border-light);
            transition: background-color 0.15s ease;
        }
        .q-item:last-child {
            border-bottom: none;
        }
        .q-item:hover {
            background-color: #FAFBFD;
        }

        /* Question Label */
        .q-label {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--sv-text);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: baseline;
            gap: 8px;
            line-height: 1.55;
        }
        .q-num {
            color: var(--sv-primary);
            font-weight: 700;
            font-size: 0.92rem;
            flex-shrink: 0;
            min-width: 28px;
        }
        .req-star {
            color: var(--sv-danger);
            font-size: 0.7rem;
            vertical-align: top;
            margin-left: 2px;
        }

        /* Hint */
        .q-hint {
            font-size: 0.8rem;
            color: var(--sv-text-secondary);
            background: var(--sv-border-light);
            padding: 8px 14px;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid var(--sv-border);
            font-weight: 500;
            margin-left: 36px;
        }

        /* ===== Input Styles ===== */
        .input-wrapper {
            margin-left: 36px;
        }

        .input-group-modern {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-group-modern .input-icon {
            position: absolute;
            left: 14px;
            color: var(--sv-text-muted);
            font-size: 1rem;
            pointer-events: none;
            z-index: 2;
        }
        .input-group-modern input,
        .input-group-modern select,
        .input-group-modern textarea {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 2.75rem;
            border-radius: var(--sv-radius-sm);
            border: 1px solid var(--sv-border);
            font-size: 0.92rem;
            color: var(--sv-text);
            background: var(--sv-card);
            font-weight: 500;
            font-family: inherit;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            -webkit-appearance: none;
            appearance: none;
        }
        .input-group-modern select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 16 16\' fill=\'%2364748b\'%3E%3Cpath d=\'M4.646 5.646a.5.5 0 0 1 .708 0L8 8.293l2.646-2.647a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 0 1 0-.708z\'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 16px;
            padding-right: 2.5rem;
            cursor: pointer;
        }
        .input-group-modern textarea {
            min-height: 100px;
            resize: vertical;
        }
        .input-group-modern input:focus,
        .input-group-modern select:focus,
        .input-group-modern textarea:focus {
            outline: none;
            border-color: var(--sv-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .input-group-modern input[readonly] {
            background-color: var(--sv-border-light);
            color: var(--sv-text-secondary);
            cursor: not-allowed;
            border-color: var(--sv-border);
        }
        .input-group-modern input::placeholder,
        .input-group-modern textarea::placeholder {
            color: var(--sv-text-muted);
            font-weight: 400;
        }

        /* ===== Segmented Radio (Ya/Tidak style) ===== */
        .segmented-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .segmented-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 24px;
            background: var(--sv-card);
            border: 1.5px solid var(--sv-border);
            border-radius: var(--sv-radius-sm);
            color: var(--sv-text-secondary);
            font-weight: 600;
            font-size: 0.88rem;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 120px;
            text-align: center;
            user-select: none;
        }
        .segmented-btn:hover {
            border-color: #CBD5E1;
            background: var(--sv-border-light);
            color: var(--sv-text);
        }
        .btn-check:checked + .segmented-btn {
            background: var(--sv-primary-light);
            border-color: var(--sv-primary);
            color: var(--sv-primary);
        }

        /* ===== Likert Scale — Compact Horizontal ===== */
        .likert-scale {
            display: flex;
            align-items: stretch;
            border: 1.5px solid var(--sv-border);
            border-radius: var(--sv-radius-sm);
            overflow: hidden;
            max-width: 480px;
        }
        .likert-option {
            flex: 1;
            text-align: center;
            cursor: pointer;
            padding: 12px 8px;
            border-right: 1px solid var(--sv-border);
            transition: all 0.2s ease;
            user-select: none;
            min-width: 0;
        }
        .likert-option:last-child {
            border-right: none;
        }
        .likert-option:hover {
            background: var(--sv-border-light);
        }
        .likert-option .likert-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--sv-text-secondary);
            display: block;
            margin-bottom: 2px;
            line-height: 1;
        }
        .likert-option .likert-label {
            font-size: 0.65rem;
            font-weight: 600;
            color: var(--sv-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: block;
            line-height: 1.2;
        }
        .btn-check:checked + .likert-option {
            background: var(--sv-primary);
            color: #fff;
        }
        .btn-check:checked + .likert-option .likert-value {
            color: #fff;
        }
        .btn-check:checked + .likert-option .likert-label {
            color: rgba(255,255,255,0.85);
        }

        /* ===== Pagination Controls ===== */
        .pagination-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 2rem;
            background: var(--sv-card);
            border-radius: var(--sv-radius);
            border: 1px solid var(--sv-border);
            margin-top: 1.5rem;
            box-shadow: var(--sv-shadow-sm);
        }
        .btn-page {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.88rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            font-family: inherit;
        }
        .btn-page-prev {
            background: var(--sv-card);
            border: 1.5px solid var(--sv-border);
            color: var(--sv-text-secondary);
        }
        .btn-page-prev:hover:not(:disabled) {
            border-color: var(--sv-primary);
            color: var(--sv-primary);
            background: var(--sv-primary-light);
        }
        .btn-page-next {
            background: var(--sv-primary);
            color: #fff;
        }
        .btn-page-next:hover:not(:disabled) {
            background: var(--sv-primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }
        .btn-page:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
        .page-indicator {
            font-size: 0.85rem;
            color: var(--sv-text-secondary);
            font-weight: 500;
        }

        /* ===== Submit Button ===== */
        .submit-container {
            margin-top: 1.5rem;
            text-align: center;
        }
        .btn-submit-modern {
            background: var(--sv-primary);
            color: #fff;
            padding: 14px 48px;
            border-radius: 50px;
            font-weight: 700;
            letter-spacing: 0.3px;
            border: none;
            font-size: 0.95rem;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-submit-modern:hover {
            background: var(--sv-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
        }

        /* ===== Privacy Footer ===== */
        .privacy-bar {
            text-align: center;
            padding: 1.25rem 1rem;
            color: var(--sv-text-muted);
            font-size: 0.82rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* ===== Mobile Responsive ===== */
        @media (max-width: 768px) {
            .section-header {
                padding: 1.25rem 1.25rem;
                gap: 10px;
            }
            .section-header-icon {
                width: 36px;
                height: 36px;
                font-size: 1rem;
            }
            .section-header-text h2 {
                font-size: 0.95rem;
            }
            .q-item {
                padding: 1.25rem 1.25rem;
            }
            .q-label {
                font-size: 0.9rem;
            }
            .q-hint {
                margin-left: 0;
                font-size: 0.75rem;
            }
            .input-wrapper {
                margin-left: 0;
            }
            .segmented-group {
                gap: 8px;
            }
            .segmented-btn {
                padding: 8px 16px;
                font-size: 0.82rem;
                min-width: 100px;
            }
            .likert-scale {
                max-width: 100%;
            }
            .likert-option {
                padding: 10px 4px;
            }
            .likert-option .likert-value {
                font-size: 0.95rem;
            }
            .likert-option .likert-label {
                font-size: 0.55rem;
            }
            .pagination-controls {
                padding: 1rem 1.25rem;
                flex-wrap: wrap;
                gap: 10px;
                justify-content: center;
            }
            .btn-page {
                padding: 8px 16px;
                font-size: 0.82rem;
            }
        }

        @media (max-width: 480px) {
            .section-header { padding: 1rem; }
            .q-item { padding: 1rem; }
            .q-label { font-size: 0.85rem; gap: 6px; }
            .q-num { min-width: 22px; font-size: 0.85rem; }
            .segmented-group { flex-direction: column; }
            .segmented-btn { min-width: auto; }
            .input-group-modern input,
            .input-group-modern select,
            .input-group-modern textarea {
                font-size: 0.85rem;
                padding: 0.7rem 0.85rem 0.7rem 2.5rem;
            }
        }
    </style>
    ';

    // --- Input icon mapping by question type ---
    $input_icons = [
        'text' => 'bi-pencil-square',
        'number' => 'bi-hash',
        'textarea' => 'bi-pencil-square',
        'select' => 'bi-chevron-down',
    ];

    // --- FORM START ---
    echo '<div class="survey-form-wrapper">';
    
    echo '<div class="form-toolbar">
            <button type="button" onclick="handleClearForm()" class="btn-clear-form">
                <i class="bi bi-trash3"></i> Kosongkan Formulir
            </button>
          </div>';
    
    echo '<form id="formKuesioner" method="POST" action="../proses_simpan.php" class="needs-validation" novalidate>';
    echo '<input type="hidden" name="csrf_token" value="'.public_csrf_token().'">';
    echo '<input type="hidden" name="jenis_kuesioner" value="'.$jenis_kuesioner.'">';
    echo '<input type="hidden" name="library_id" value="'.$library_id.'">';

    echo '<script>
    function handleClearForm() {
        Swal.fire({
            title: "Kosongkan Formulir?",
            text: "Seluruh jawaban yang telah Anda isi akan dihapus permanen.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#EF4444",
            cancelButtonColor: "#64748B",
            confirmButtonText: "Ya, Kosongkan",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                if(window.clearSurveyDraft) window.clearSurveyDraft();
                document.getElementById("formKuesioner").reset();
                document.querySelectorAll(".btn-check").forEach(radio => radio.checked = false);
                window.location.reload();
            }
        });
    }
    </script>';

    // --- LOOP KATEGORI & COLLECT DATA ---
    $nomor_soal = 1;
    $navigator_data = [];
    $section_question_counts = [];
    foreach ($pertanyaan_ordered as $kategori => $items) {
        $section_question_counts[$kategori] = count($items);
        foreach ($items as $p) {
            $navigator_data[] = [
                'num' => $nomor_soal++,
                'id' => $p['id'],
                'section' => $kategori
            ];
        }
    }

    // Render sections
    $nomor_soal = 1;
    $section_index = 0;
    foreach ($pertanyaan_ordered as $kategori => $items) {
        $icon = get_section_icon($kategori, $section_icons);
        $activeClass = ($section_index === 0) ? ' active' : '';
        $qCount = count($items);
        
        echo '<div class="section-card'.$activeClass.'" data-section-index="'.$section_index.'" data-section-name="'.htmlspecialchars($kategori).'">';
        
        // Header
        echo '<div class="section-header">
                <div class="section-header-icon"><i class="bi '.$icon.'"></i></div>
                <div class="section-header-text">
                    <h2>'.htmlspecialchars($kategori).'</h2>
                    <p>Lengkapi informasi pada bagian ini</p>
                </div>
              </div>';
        
        echo '<div>';

        foreach ($items as $index => $p) {
            $id = $p['id'];
            $label = htmlspecialchars($p['teks_pertanyaan']);
            $tipe = $p['tipe_input'];
            $keterangan = htmlspecialchars($p['keterangan'] ?? '');
            
            // Auto-fill Logic
            $val = isset($defaults[$label]) ? $defaults[$label] : '';
            if ($val === '' && $jenis_kuesioner === 'IPLM') {
                if ($auto_ids['jenis'] && (int)$id === (int)$auto_ids['jenis']) {
                    $val = $defaults['core_jenis'] ?? '';
                } elseif ($auto_ids['subjenis'] && (int)$id === (int)$auto_ids['subjenis']) {
                    $val = $defaults['core_subjenis'] ?? '';
                } elseif ($auto_ids['nama'] && (int)$id === (int)$auto_ids['nama']) {
                    $val = $defaults['core_nama'] ?? '';
                }
            }
            if ($val === '' && $jenis_kuesioner === 'IPLM') {
                $label_raw = strtolower(trim($p['teks_pertanyaan']));
                if (strpos($label_raw, 'sub jenis') !== false || strpos($label_raw, 'subjenis') !== false) {
                    $val = $defaults['core_subjenis'] ?? '';
                } elseif (strpos($label_raw, 'jenis perpustakaan') !== false) {
                    $val = $defaults['core_jenis'] ?? '';
                } elseif (strpos($label_raw, 'nama perpustakaan') !== false) {
                    $val = $defaults['core_nama'] ?? '';
                }
            }
            $readonly = ($val !== '') ? 'readonly' : '';

            // Parsing Opsi
            $opsi_custom = [];
            if (!empty($p['pilihan_opsi'])) {
                $opsi_custom = array_map('trim', explode(',', $p['pilihan_opsi']));
            }

            echo '<div class="q-item" id="q_wrapper_'.$id.'" data-q-id="'.$id.'">';
            
            // Label
            echo '<div class="q-label">';
            echo '<span class="q-num">'.$nomor_soal.'.</span>';
            echo '<span>' . $label;
            if (empty($readonly)) echo '<span class="req-star" title="Wajib diisi">*</span>';
            echo '</span>';
            echo '</div>';
            $nomor_soal++;

            // Keterangan
            if (!empty($keterangan)) {
                echo '<div class="q-hint"><i class="bi bi-info-circle"></i> '.$keterangan.'</div>';
            }

            // Input wrapper
            echo '<div class="input-wrapper">';

            // A. Text / Number
            if ($tipe == 'text' || $tipe == 'number') {
                $inputIcon = ($tipe == 'number') ? 'bi-hash' : 'bi-pencil-square';
                // Detect specific field types for better icons
                $label_lower = strtolower($p['teks_pertanyaan']);
                if (strpos($label_lower, 'usia') !== false || strpos($label_lower, 'umur') !== false) $inputIcon = 'bi-calendar3';
                if (strpos($label_lower, 'whatsapp') !== false || strpos($label_lower, 'kontak') !== false || strpos($label_lower, 'telepon') !== false || strpos($label_lower, 'hp') !== false) $inputIcon = 'bi-whatsapp';
                if (strpos($label_lower, 'nama') !== false) $inputIcon = 'bi-person';
                if (strpos($label_lower, 'email') !== false) $inputIcon = 'bi-envelope';
                if (strpos($label_lower, 'pekerjaan') !== false) $inputIcon = 'bi-briefcase';
                
                echo '<div class="input-group-modern">
                        <i class="bi '.$inputIcon.' input-icon"></i>
                        <input type="'.$tipe.'" id="inp_'.$id.'" name="jawaban['.$id.']" 
                               value="'.$val.'" '.$readonly.' required 
                               placeholder="Masukkan jawaban Anda">
                      </div>';
            }
            
            // B. Textarea
            elseif ($tipe == 'textarea') {
                echo '<div class="input-group-modern">
                        <i class="bi bi-pencil-square input-icon" style="top:14px;"></i>
                        <textarea id="inp_'.$id.'" name="jawaban['.$id.']" rows="3" required 
                                  placeholder="Tuliskan jawaban lengkap...">'.$val.'</textarea>
                      </div>';
            }
            
            // C. Dropdown (Select)
            elseif ($tipe == 'select') {
                $selectIcon = 'bi-list-ul';
                $label_lower = strtolower($p['teks_pertanyaan']);
                if (strpos($label_lower, 'pendidikan') !== false) $selectIcon = 'bi-mortarboard';
                if (strpos($label_lower, 'provinsi') !== false || strpos($label_lower, 'kabupaten') !== false || strpos($label_lower, 'kota') !== false) $selectIcon = 'bi-geo-alt';
                if (strpos($label_lower, 'jenis kelamin') !== false || strpos($label_lower, 'gender') !== false) $selectIcon = 'bi-people';
                
                echo '<div class="input-group-modern">
                        <i class="bi '.$selectIcon.' input-icon"></i>
                        <select id="inp_'.$id.'" name="jawaban['.$id.']" required>';
                echo '<option value="" selected disabled>Pilih jawaban Anda</option>';
                
                $list_opsi = !empty($opsi_custom) ? $opsi_custom : ['Ya', 'Tidak'];
                
                foreach ($list_opsi as $opt) {
                    $opt_safe = htmlspecialchars($opt, ENT_QUOTES);
                    echo '<option value="'.$opt_safe.'">'.$opt_safe.'</option>';
                }
                echo '</select></div>';
            }
            
            // D. Radio Button (Segmented buttons)
            elseif ($tipe == 'radio') {
                $list_opsi = [];
                foreach($opsi_custom as $oc) $list_opsi[$oc] = $oc;
                
                echo '<div class="segmented-group">';
                foreach ($list_opsi as $val_opt => $label_opt) {
                    $val_safe = htmlspecialchars((string)$val_opt, ENT_QUOTES);
                    $label_safe = htmlspecialchars((string)$label_opt, ENT_QUOTES);
                    $id_safe = preg_replace('/[^a-zA-Z0-9_\-]/', '_', (string)$val_opt);
                    echo '<div>
                            <input type="radio" class="btn-check" name="jawaban['.$id.']" id="opt_'.$id.'_'.$id_safe.'" value="'.$val_safe.'" required>
                            <label class="segmented-btn" for="opt_'.$id.'_'.$id_safe.'">'.$label_safe.'</label>
                          </div>';
                }
                echo '</div>';
            }
            
            // E. Likert Scale — Compact Horizontal
            elseif ($tipe == 'likert') {
                $list_opsi = [
                    '1' => 'STS',
                    '2' => 'TS',
                    '3' => 'S',
                    '4' => 'SS'
                ];
                
                echo '<div class="likert-scale">';
                foreach ($list_opsi as $val_opt => $label_opt) {
                    $val_safe = htmlspecialchars((string)$val_opt, ENT_QUOTES);
                    echo '<input type="radio" class="btn-check" name="jawaban['.$id.']" id="opt_'.$id.'_'.$val_safe.'" value="'.$val_safe.'" required>
                          <label class="likert-option" for="opt_'.$id.'_'.$val_safe.'">
                              <span class="likert-value">'.$val_safe.'</span>
                              <span class="likert-label">'.$label_opt.'</span>
                          </label>';
                }
                echo '</div>';
            }

            echo '</div>'; // End input-wrapper
            echo '</div>'; // End q-item
        }
        echo '</div>'; // End content wrapper
        echo '</div>'; // End section card
        
        $section_index++;
    }

    // --- PAGINATION CONTROLS ---
    echo '
    <div class="pagination-controls" id="paginationControls">
        <button type="button" class="btn-page btn-page-prev" id="btnPrev" onclick="navigateSection(-1)" disabled>
            <i class="bi bi-arrow-left"></i> Sebelumnya
        </button>
        <span class="page-indicator" id="pageIndicator">Halaman 1 dari '.$total_sections.'</span>
        <button type="button" class="btn-page btn-page-next" id="btnNext" onclick="navigateSection(1)">
            Selanjutnya <i class="bi bi-arrow-right"></i>
        </button>
    </div>';

    // --- SUBMIT (hidden until last page) ---
    echo '
    <div class="submit-container" id="submitContainer" style="display:none;">
        <button type="button" onclick="konfirmasiKirim()" class="btn-submit-modern">
            <i class="bi bi-send-check"></i> Simpan Jawaban
        </button>
    </div>';

    // --- PRIVACY BAR ---
    echo '
    <div class="privacy-bar">
        <i class="bi bi-shield-lock-fill"></i>
        Data Anda aman dan hanya digunakan untuk keperluan survei literasi masyarakat.
    </div>';
    
    echo '</form>';
    echo '</div>'; // End survey-form-wrapper

    // --- JS LOGIC ---
    echo "
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formKuesioner');
        const totalSections = " . $total_sections . ";
        const totalQuestions = " . $total_questions . ";
        let currentSection = 0;

        // ===== PAGINATION =====
        function showSection(index) {
            document.querySelectorAll('.section-card').forEach(card => {
                card.classList.remove('active');
            });
            const target = document.querySelector('.section-card[data-section-index=\"' + index + '\"]');
            if (target) {
                target.classList.add('active');
                // Scroll to top of form area
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            
            // Update pagination buttons
            document.getElementById('btnPrev').disabled = (index === 0);
            document.getElementById('pageIndicator').textContent = 'Halaman ' + (index + 1) + ' dari ' + totalSections;
            
            const isLast = (index === totalSections - 1);
            document.getElementById('btnNext').style.display = isLast ? 'none' : '';
            document.getElementById('submitContainer').style.display = isLast ? '' : 'none';
            
            // Update sidebar if exists
            if (window.updateSidebarActive) {
                window.updateSidebarActive(index);
            }
            
            currentSection = index;
        }

        window.navigateSection = function(direction) {
            const newIndex = currentSection + direction;
            if (newIndex >= 0 && newIndex < totalSections) {
                showSection(newIndex);
            }
        };

        // Allow sidebar to jump to section
        window.jumpToSection = function(index) {
            if (index >= 0 && index < totalSections) {
                showSection(index);
            }
        };

        // ===== AUTO-SCROLL TO NEXT QUESTION =====
        form.addEventListener('change', function(e) {
            if (e.target.matches('input[type=\"radio\"]') || e.target.tagName === 'SELECT') {
                scrollToNext(e.target);
            }
        });

        form.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                if (e.target.matches('input[type=\"text\"], input[type=\"number\"]')) {
                    e.preventDefault();
                    scrollToNext(e.target, true);
                }
            }
        });

        function scrollToNext(currentElement, focusNext = false) {
            const currentItem = currentElement.closest('.q-item');
            if (!currentItem) return;

            let nextItem = currentItem.nextElementSibling;
            // If nextItem is not a q-item (could be end of section), check within wrapper
            if (nextItem && !nextItem.classList.contains('q-item')) nextItem = null;
            
            if (!nextItem) {
                // We're at the last question of the current section
                // Don't auto-advance to next section
                return;
            }

            if (nextItem) {
                const scrollDelay = focusNext ? 0 : 300;
                setTimeout(() => {
                    nextItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    if (focusNext) {
                        const nextInput = nextItem.querySelector('input:not([type=\"radio\"]):not([type=\"hidden\"]), select, textarea');
                        if (nextInput) {
                            nextInput.focus({ preventScroll: true });
                        }
                    }
                }, scrollDelay);
            }
        }

        // ===== PERSISTENCE (localStorage) =====
        const gJenis = document.querySelector('input[name=\"jenis_kuesioner\"]');
        const gLib = document.querySelector('input[name=\"library_id\"]');
        if (!gJenis || !gLib) return;

        const storageKey = 'survey_draft_' + gJenis.value + '_' + gLib.value;
        
        // Restore from storage
        const savedData = JSON.parse(localStorage.getItem(storageKey) || '{}');
        Object.keys(savedData).forEach(name => {
            const input = form.querySelector(\"[name=\\\"\" + name + \"\\\"]\");
            if (!input) return;

            if (input.type === 'radio') {
                const targetRadio = form.querySelector(\"[name=\\\"\" + name + \"\\\"][value=\\\"\" + savedData[name] + \"\\\"]\");
                if (targetRadio) targetRadio.checked = true;
            } else {
                input.value = savedData[name];
            }
        });

        // Save on change
        form.addEventListener('input', function(e) {
            if (e.target.name && e.target.name.startsWith('jawaban[')) {
                const currentDraft = JSON.parse(localStorage.getItem(storageKey) || '{}');
                currentDraft[e.target.name] = e.target.value;
                localStorage.setItem(storageKey, JSON.stringify(currentDraft));
            }
        });

        window.clearSurveyDraft = () => localStorage.removeItem(storageKey);

        // ===== PROGRESS TRACKING =====
        window.getSurveyProgress = function() {
            let filledCount = 0;
            let sectionProgress = {};

            document.querySelectorAll('.section-card').forEach(card => {
                const sectionName = card.getAttribute('data-section-name');
                const sectionIdx = card.getAttribute('data-section-index');
                let sectionFilled = 0;
                let sectionTotal = 0;

                card.querySelectorAll('.q-item').forEach(item => {
                    sectionTotal++;
                    const inputs = item.querySelectorAll('input, select, textarea');
                    let isFilled = false;

                    inputs.forEach(inp => {
                        if (inp.type === 'hidden') return;
                        if (inp.type === 'radio') {
                            if (inp.checked) isFilled = true;
                        } else {
                            if (inp.value.trim() !== '') isFilled = true;
                        }
                    });

                    if (isFilled) {
                        sectionFilled++;
                        filledCount++;
                    }
                });

                sectionProgress[sectionIdx] = {
                    name: sectionName,
                    filled: sectionFilled,
                    total: sectionTotal
                };
            });

            return {
                filledCount: filledCount,
                totalQuestions: totalQuestions,
                percentage: totalQuestions > 0 ? Math.round((filledCount / totalQuestions) * 100) : 0,
                sections: sectionProgress
            };
        };

        // Update progress on interaction
        const updateProgress = () => {
            const progress = window.getSurveyProgress();
            // Update hero progress if exists
            if (window.updateHeroProgress) {
                window.updateHeroProgress(progress);
            }
            // Update sidebar progress if exists
            if (window.updateSidebarProgress) {
                window.updateSidebarProgress(progress);
            }
        };

        setTimeout(updateProgress, 300);
        form.addEventListener('input', updateProgress);
        form.addEventListener('change', updateProgress);

        // Show first section
        showSection(0);
    });

    function konfirmasiKirim() {
        const form = document.getElementById('formKuesioner');
        
        // Validate ALL sections, not just visible one
        const allSections = document.querySelectorAll('.section-card');
        let firstInvalidSection = -1;
        let firstInvalid = null;

        allSections.forEach(section => {
            section.classList.add('active'); // Temporarily show all for validation
        });

        if (!form.checkValidity()) {
            firstInvalid = form.querySelector(':invalid');
            if (firstInvalid) {
                const parentSection = firstInvalid.closest('.section-card');
                if (parentSection) {
                    firstInvalidSection = parseInt(parentSection.getAttribute('data-section-index'));
                }
            }
        }

        // Restore pagination state
        allSections.forEach(section => {
            section.classList.remove('active');
        });

        if (firstInvalidSection >= 0) {
            window.jumpToSection(firstInvalidSection);
            setTimeout(() => {
                form.reportValidity();
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    const parent = firstInvalid.closest('.q-item');
                    if (parent) {
                        parent.style.backgroundColor = '#FEF2F2';
                        setTimeout(() => parent.style.backgroundColor = '', 2000);
                    }
                    firstInvalid.focus();
                }
            }, 100);
            return;
        }

        // Re-show current section
        const currentIdx = parseInt(document.querySelector('.section-card.active')?.getAttribute('data-section-index') || allSections.length - 1);
        window.jumpToSection(currentIdx >= 0 ? currentIdx : allSections.length - 1);

        Swal.fire({
            title: 'Konfirmasi Kirim',
            text: 'Pastikan seluruh jawaban sudah sesuai. Lanjutkan?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#2563EB',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Ya, Kirim Data',
            cancelButtonText: 'Periksa Lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show all sections before submit so all fields are submitted
                document.querySelectorAll('.section-card').forEach(s => s.classList.add('active'));
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                if (window.clearSurveyDraft) window.clearSurveyDraft();
                form.submit();
            }
        });
    }
    </script>
    ";
}
?>
