<?php
namespace Controllers;

use Core\Controller;
use PDO;
use Exception;
use finfo;

class AdminController extends Controller {

    private function requireAuth() {
        if (!session_id()) session_start();
        require_once BASE_PATH . 'config/admin_auth.php';
        if (empty($_SESSION['admin_logged_in'])) {
            $this->redirect('/auth/login');
            exit;
        }
    }

    public function index() {
        $this->redirect('/admin/dashboard');
    }

    public function login() {
        $this->redirect('/auth/login');
    }

    public function logout() {
        $this->redirect('/auth/logout');
    }

    public function dashboard() {
        $this->requireAuth();
        
        date_default_timezone_set('Asia/Makassar'); 

        $dashboard_filter = $_SESSION['dashboard_filter'] ?? [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter_chart'])) {
            $dashboard_filter['tahun_chart'] = $_POST['tahun_chart'] ?? date('Y');
            $_SESSION['dashboard_filter'] = $dashboard_filter;
            $this->redirect('/admin/dashboard'); return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter_range'])) {
            $dashboard_filter['range_start_bulan'] = $_POST['range_start_bulan'] ?? '01';
            $dashboard_filter['range_start_tahun'] = $_POST['range_start_tahun'] ?? date('Y');
            $dashboard_filter['range_end_bulan'] = $_POST['range_end_bulan'] ?? date('m');
            $dashboard_filter['range_end_tahun'] = $_POST['range_end_tahun'] ?? date('Y');
            $_SESSION['dashboard_filter'] = $dashboard_filter;
            $this->redirect('/admin/dashboard'); return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter_periode'])) {
            $dashboard_filter['bulan'] = $_POST['bulan'] ?? date('m');
            $dashboard_filter['tahun'] = $_POST['tahun'] ?? date('Y');
            $_SESSION['dashboard_filter'] = $dashboard_filter;
            $this->redirect('/admin/dashboard'); return;
        }
        if (!empty($_GET)) {
            $keys = ['tahun_chart','range_start_bulan','range_start_tahun','range_end_bulan','range_end_tahun','bulan','tahun'];
            $incoming = [];
            foreach ($keys as $k) {
                if (isset($_GET[$k])) $incoming[$k] = $_GET[$k];
            }
            if (!empty($incoming)) {
                $_SESSION['dashboard_filter'] = array_merge($dashboard_filter, $incoming);
                $this->redirect('/admin/dashboard'); return;
            }
        }

        $settingModel = $this->model('SettingModel');
        $dashboardModel = $this->model('DashboardModel');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['aksi_status']) && $_POST['aksi_status'] == 'toggle') {
                $kunci = $_POST['kunci']; 
                $status_baru = $_POST['status_baru'];
                $settingModel->updateSetting($kunci, $status_baru);
                
                $kunci_mode = ($kunci == 'status_iplm') ? 'iplm_mode' : 'tkm_mode';
                $settingModel->updateSetting($kunci_mode, 'manual');
                $this->redirect('/admin/dashboard'); return;
            }
            if (isset($_POST['aksi_status']) && $_POST['aksi_status'] == 'save_schedule') {
                $jenis = $_POST['jenis'];
                $mode  = $_POST['mode'];
                $start = str_replace('T', ' ', $_POST['start_date']);
                $end   = str_replace('T', ' ', $_POST['end_date']);

                $settingModel->updateSetting($jenis . '_mode', $mode);
                $settingModel->updateSetting($jenis . '_start', $start);
                $settingModel->updateSetting($jenis . '_end', $end);
                $this->redirect('/admin/dashboard'); return;
            }
        }

        $settings = $settingModel->getAllSettings();

        $infoIPLM = $this->getStatusInfo('iplm', $settings);
        $infoTKM  = $this->getStatusInfo('tkm', $settings);

        $tahun_chart = $dashboard_filter['tahun_chart'] ?? date('Y');
        $data_iplm_chart = $dashboardModel->getMonthlyStats('IPLM', $tahun_chart);
        $data_tkm_chart  = $dashboardModel->getMonthlyStats('TKM', $tahun_chart);
        $total_responden_tahunan = array_sum($data_iplm_chart) + array_sum($data_tkm_chart);
        $chart_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $list_bulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
        $range_start_bulan = str_pad((int)($dashboard_filter['range_start_bulan'] ?? '01'), 2, '0', STR_PAD_LEFT);
        $range_start_tahun = (int)($dashboard_filter['range_start_tahun'] ?? date('Y'));
        $range_end_bulan = str_pad((int)($dashboard_filter['range_end_bulan'] ?? date('m')), 2, '0', STR_PAD_LEFT);
        $range_end_tahun = (int)($dashboard_filter['range_end_tahun'] ?? date('Y'));

        $range_start_key = sprintf('%04d-%02d', $range_start_tahun, (int)$range_start_bulan);
        $range_end_key = sprintf('%04d-%02d', $range_end_tahun, (int)$range_end_bulan);

        if ($range_start_key > $range_end_key) {
            $tmp_key = $range_start_key; $range_start_key = $range_end_key; $range_end_key = $tmp_key;
            $tmp_bulan = $range_start_bulan; $range_start_bulan = $range_end_bulan; $range_end_bulan = $tmp_bulan;
            $tmp_tahun = $range_start_tahun; $range_start_tahun = $range_end_tahun; $range_end_tahun = $tmp_tahun;
        }

        $range_label = ($range_start_key === $range_end_key)
            ? ($list_bulan[$range_start_bulan] ?? $range_start_bulan) . " " . $range_start_tahun
            : ($list_bulan[$range_start_bulan] ?? $range_start_bulan) . " " . $range_start_tahun . " - " . ($list_bulan[$range_end_bulan] ?? $range_end_bulan) . " " . $range_end_tahun;

        $total_range_iplm = $dashboardModel->getRangeStats('IPLM', $range_start_key, $range_end_key);
        $total_range_tkm = $dashboardModel->getRangeStats('TKM', $range_start_key, $range_end_key);
        $total_responden_range = (int)$total_range_iplm + (int)$total_range_tkm;

        $tkm_usia_stats = $dashboardModel->getTkmDemographics('usia', $range_start_key, $range_end_key);
        $tkm_gender_stats = $dashboardModel->getTkmDemographics('gender', $range_start_key, $range_end_key);

        $bulan_pilih = $dashboard_filter['bulan'] ?? date('m');
        $tahun_pilih = $dashboard_filter['tahun'] ?? date('Y');
        $label_periode = $list_bulan[$bulan_pilih] . " " . $tahun_pilih;

        $data = compact('infoIPLM', 'infoTKM', 'tahun_chart', 'data_iplm_chart', 'data_tkm_chart', 'total_responden_tahunan', 'chart_labels', 'list_bulan', 'range_start_bulan', 'range_start_tahun', 'range_end_bulan', 'range_end_tahun', 'range_label', 'total_range_iplm', 'total_range_tkm', 'total_responden_range', 'bulan_pilih', 'tahun_pilih', 'label_periode', 'settings', 'tkm_usia_stats', 'tkm_gender_stats');

        $this->view('admin/dashboard', $data);
    }

    public function pengaduan() {
        $this->requireAuth();
        
        $complaintModel = $this->model('ComplaintModel');
        $complaintModel->ensureColumnsExist();

        if (isset($_POST['aksi']) && $_POST['aksi'] == 'hapus') {
            $complaintModel->deleteComplaint($_POST['id']);
            $this->redirect('/admin/pengaduan'); return;
        }
        if (isset($_POST['aksi']) && $_POST['aksi'] == 'toggle_important') {
            $complaintModel->toggleImportant($_POST['id']);
            $this->redirect('/admin/pengaduan'); return;
        }
        if (isset($_POST['aksi']) && $_POST['aksi'] == 'toggle_done') {
            $complaintModel->toggleDone($_POST['id']);
            $this->redirect('/admin/pengaduan'); return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter_pengaduan'])) {
            $_SESSION['pengaduan_filter'] = [
                'bulan' => $_POST['bulan'] ?? date('m'),
                'tahun' => $_POST['tahun'] ?? date('Y'),
                'penting' => $_POST['penting'] ?? ''
            ];
            $this->redirect('/admin/pengaduan'); return;
        }

        if (!empty($_GET)) {
            $session_filter = [];
            if (isset($_GET['bulan'])) $session_filter['bulan'] = $_GET['bulan'];
            if (isset($_GET['tahun'])) $session_filter['tahun'] = $_GET['tahun'];
            if (isset($_GET['penting'])) $session_filter['penting'] = $_GET['penting'];
            if (!empty($session_filter)) {
                $_SESSION['pengaduan_filter'] = $session_filter;
                $this->redirect('/admin/pengaduan'); return;
            }
        }

        $list_bulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
        $pengaduan_filter = $_SESSION['pengaduan_filter'] ?? [];
        $bulan_pilih = isset($pengaduan_filter['bulan']) ? str_pad($pengaduan_filter['bulan'], 2, '0', STR_PAD_LEFT) : date('m');
        $tahun_pilih = isset($pengaduan_filter['tahun']) ? (int)$pengaduan_filter['tahun'] : (int)date('Y');
        $filter_penting = $pengaduan_filter['penting'] ?? '';

        $grafik_raw = $complaintModel->getGrafikData($tahun_pilih);
        $grafik_counts = array_fill(1, 12, 0);
        $grafik_important = array_fill(1, 12, 0);
        $grafik_done = array_fill(1, 12, 0);
        foreach ($grafik_raw as $row) {
            $idx = (int)$row['bln'];
            $grafik_counts[$idx] = (int)$row['total'];
            $grafik_important[$idx] = (int)$row['important_count'];
            $grafik_done[$idx] = (int)$row['done_count'];
        }

        $data = $complaintModel->getFilteredComplaints((int)$bulan_pilih, $tahun_pilih, $filter_penting);
        $total_aduan = count($data);

        $viewData = compact('list_bulan', 'bulan_pilih', 'tahun_pilih', 'filter_penting', 'grafik_counts', 'grafik_important', 'grafik_done', 'data', 'total_aduan');
        
        $this->view('admin/pengaduan', $viewData);
    }

    private function getStatusInfo($jenis, $settings) {
        $mode = $settings[$jenis . '_mode'] ?? 'manual';
        $manualStatus = $settings['status_' . $jenis] ?? 'buka';
        $start = str_replace('T', ' ', $settings[$jenis . '_start'] ?? '');
        $end = str_replace('T', ' ', $settings[$jenis . '_end'] ?? '');
        $now = time();
        $isOpen = false; $label = ""; $desc = "";
    
        if ($mode == 'manual') {
            $isOpen = ($manualStatus == 'buka');
            $label = $isOpen ? "MANUAL: DIBUKA" : "MANUAL: DITUTUP";
            $desc = "Diatur secara manual.";
        } else {
            if ($start && $end) {
                $startTs = strtotime($start);
                $endTs = strtotime($end);
                if ($startTs && $endTs && $now >= $startTs && $now <= $endTs) {
                    $isOpen = true; $label = "TERJADWAL: BERJALAN"; $desc = "Tutup: ".date('d M H:i', $endTs);
                } elseif ($startTs && $now < $startTs) {
                    $isOpen = false; $label = "TERJADWAL: MENUNGGU"; $desc = "Buka: ".date('d M H:i', $startTs);
                } else {
                    $isOpen = false; $label = "TERJADWAL: SELESAI"; $desc = "Tutup: ".date('d M H:i', $endTs ?: strtotime($end));
                }
            } else {
                $isOpen = false; $label = "TERJADWAL: BELUM SET"; $desc = "Atur tanggal dulu.";
            }
        }
        return ['open' => $isOpen, 'label' => $label, 'desc' => $desc, 'mode' => $mode];
    }

    public function perpustakaan() {
        $this->requireAuth();
        global $pdo;
            

    // --- 0. AMBIL MASTER KATEGORI ---
    $stmtK = $pdo->query("SELECT * FROM master_kategori ORDER BY kategori, sub_kategori");
    $rawKat = $stmtK->fetchAll(PDO::FETCH_ASSOC);

    $strukturJenis = [];
    $validasiMap = []; 
    $formatFixer = [];

    foreach ($rawKat as $r) {
        $strukturJenis[$r['kategori']][] = $r['sub_kategori'];
        $uKat = strtoupper($r['kategori']);
        $uSub = strtoupper($r['sub_kategori']);
        $validasiMap[$uKat][] = $uSub;
        $formatFixer['KAT'][$uKat] = $r['kategori'];
        $formatFixer['SUB'][$uSub] = $r['sub_kategori'];
    }

    function isKategoriValid($kategori, $subjenis, $map) {
        $k = strtoupper(trim($kategori));
        $s = strtoupper(trim($subjenis));
        return isset($map[$k]) && in_array($s, $map[$k]);
    }

    if (!isset($_GET['ajax_action'])) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter_periode'])) {
            $_SESSION['perpustakaan_filter'] = [
                'bulan' => $_POST['bulan'] ?? date('m'),
                'tahun' => $_POST['tahun'] ?? date('Y')
            ];
            $this->redirect('/admin/perpustakaan'); return;
        }
        if (!empty($_GET) && (isset($_GET['bulan']) || isset($_GET['tahun']))) {
            $_SESSION['perpustakaan_filter'] = [
                'bulan' => $_GET['bulan'] ?? date('m'),
                'tahun' => $_GET['tahun'] ?? date('Y')
            ];
            $this->redirect('/admin/perpustakaan'); return;
        }
    }

    // --- 1. HANDLE REQUEST AJAX (LIVE SEARCH & STATUS IPLM) ---
    if (isset($_GET['ajax_action']) && $_GET['ajax_action'] == 'load_table') {
        
        // Filter Params
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $filterKat = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';
        $filterSub = isset($_GET['subjenis']) ? trim($_GET['subjenis']) : '';
        $filterStatus = isset($_GET['status_iplm']) ? trim($_GET['status_iplm']) : '';
        
        // Status Filter Params (Bulan & Tahun) - KHUSUS IPLM
        $filterBulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
        $filterTahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 50;
        $offset = ($page - 1) * $limit;

        // START TRY BLOCK
        try {
            // Build Query WHERE
            $whereParts = [];
            $params = [];

            if ($search) {
                $searchLower = strtolower($search);
                $whereParts[] = "(LOWER(nama) LIKE :search OR LOWER(jenis) LIKE :search)";
                $params[':search'] = "%$searchLower%";
            }
            if ($filterKat) {
                $whereParts[] = "kategori = :kat";
                $params[':kat'] = $filterKat;
            }
            if ($filterSub) {
                $whereParts[] = "jenis = :sub";
                $params[':sub'] = $filterSub;
            }
            if ($filterStatus === 'sudah') {
                $whereParts[] = "(SELECT COUNT(*) FROM trans_header th WHERE th.library_id = l.id AND th.jenis_kuesioner = 'IPLM' AND th.periode_bulan = :bln AND th.periode_tahun = :thn) > 0";
            } elseif ($filterStatus === 'belum') {
                $whereParts[] = "(SELECT COUNT(*) FROM trans_header th WHERE th.library_id = l.id AND th.jenis_kuesioner = 'IPLM' AND th.periode_bulan = :bln AND th.periode_tahun = :thn) = 0";
            }

            $whereClause = $whereParts ? "WHERE " . implode(" AND ", $whereParts) : "";

            $params[':bln'] = $filterBulan;
            $params[':thn'] = $filterTahun;

            // Hitung Total Data
            $sqlCount = "SELECT COUNT(*) FROM libraries l $whereClause";
            $stmtCount = $pdo->prepare($sqlCount);
            foreach ($params as $key => $val) {
                if (strpos($sqlCount, $key) !== false) $stmtCount->bindValue($key, $val);
            }
            $stmtCount->execute();
            $total_data = $stmtCount->fetchColumn();
            $total_pages = ceil($total_data / $limit);

            // Main Query
            $sql = "SELECT l.*, 
                    (SELECT COUNT(*) FROM trans_header th WHERE th.library_id = l.id AND th.jenis_kuesioner = 'IPLM' AND th.periode_bulan = :bln AND th.periode_tahun = :thn) as status_iplm
                    FROM libraries l 
                    $whereClause 
                    ORDER BY 
                    CASE 
                        WHEN kategori = 'Umum' THEN 1 
                        WHEN kategori = 'Sekolah' THEN 2 
                        WHEN kategori = 'Khusus' THEN 3 
                        ELSE 4 
                    END ASC, 
                    nama ASC 
                    LIMIT $limit OFFSET $offset";
            
            $stmt = $pdo->prepare($sql);
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            $stmt->execute();
            $libraries = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Render HTML Baris Tabel
            if (empty($libraries)) {
                echo '<tr><td colspan="7" class="text-center py-5 text-muted">Tidak ada data ditemukan.</td></tr>';
            } else {
                $no = $offset + 1;
                foreach ($libraries as $lib) {
                    $kat = $lib['kategori'] ?? 'Umum';
                    $bg = ($kat == 'Sekolah') ? 'bg-primary' : (($kat == 'Khusus') ? 'bg-warning text-dark' : 'bg-success');
                    
                    $badgeIplm = ($lib['status_iplm'] > 0) 
                        ? '<span class="badge bg-success rounded-pill"><i class="bi bi-check-circle-fill me-1"></i>Sudah Mengisi</span>' 
                        : '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill"><i class="bi bi-x-circle me-1"></i>Belum</span>';
                    
                    echo '<tr>';
                    echo '<td class="text-center"><input type="checkbox" class="form-check-input check-item" value="' . $lib['id'] . '" onchange="handleCheckboxChange(this)"></td>';
                    echo '<td class="text-center fw-bold">' . $no++ . '</td>';
                    echo '<td class="fw-bold text-uppercase">' . htmlspecialchars($lib['nama']) . '</td>';
                    echo '<td><span class="badge ' . $bg . ' badge-kategori">' . htmlspecialchars($kat) . '</span></td>';
                    echo '<td>' . htmlspecialchars($lib['jenis']) . '</td>';
                    
                    $statusText = ($lib['status_iplm'] > 0) ? 'sudah' : 'belum';
                    echo '<td class="text-center">
                            <button type="button" class="btn btn-link p-0 text-decoration-none btn-status-iplm"
                                data-id="' . $lib['id'] . '"
                                data-status="' . $statusText . '"
                                data-nama="' . htmlspecialchars($lib['nama']) . '">
                                ' . $badgeIplm . '
                            </button>
                        </td>';

                    echo '<td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-warning btn-edit-lib"
                                        data-id="' . $lib['id'] . '"
                                        data-nama="' . htmlspecialchars($lib['nama']) . '"
                                        data-kategori="' . htmlspecialchars($lib['kategori'] ?? '') . '"
                                        data-subjenis="' . htmlspecialchars($lib['jenis']) . '">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-danger" onclick="deleteSingle(' . $lib['id'] . ')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>';
                    echo '</tr>';
                }
                
                // PAGINATION
                if ($total_pages > 1) {
                    echo '<tr id="pagination-row"><td colspan="7" class="p-0 border-0"><nav class="mt-4"><ul class="pagination pagination-sm justify-content-center">';
                    
                    $prevDisabled = ($page <= 1) ? 'disabled' : '';
                    $prevPage = max(1, $page - 1);
                    echo '<li class="page-item ' . $prevDisabled . '"><a class="page-link" href="#" onclick="loadTable(' . $prevPage . '); return false;">&laquo;</a></li>';

                    $range = 2;
                    $start = max(1, $page - $range);
                    $end = min($total_pages, $page + $range);

                    if ($start > 1) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';

                    for ($i = $start; $i <= $end; $i++) {
                        $isActive = ($i == $page) ? 'active' : '';
                        $bgStyle = ($i == $page) ? 'bg-dark border-dark' : 'text-dark';
                        if ($i == $page) {
                            echo '<li class="page-item active"><span class="page-link bg-dark border-dark">' . $i . '</span></li>';
                        } else {
                            echo '<li class="page-item"><a class="page-link text-dark" href="#" onclick="loadTable(' . $i . '); return false;">' . $i . '</a></li>';
                        }
                    }

                    if ($end < $total_pages) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';

                    $nextDisabled = ($page >= $total_pages) ? 'disabled' : '';
                    $nextPage = min($total_pages, $page + 1);
                    echo '<li class="page-item ' . $nextDisabled . '"><a class="page-link" href="#" onclick="loadTable(' . $nextPage . '); return false;">&raquo;</a></li>';

                    echo '</ul><div class="text-center mt-2 small text-muted">Halaman ' . $page . ' dari ' . $total_pages . '</div></nav></td></tr>';
                }
            }
        } catch (Exception $e) {
            echo '<tr><td colspan="7" class="text-center text-danger py-5">Terjadi kesalahan server. Silakan coba lagi nanti.</td></tr>';
        }
        exit;
    }

    // --- 2. PROSES CRUD (POST) ---
    $pesan = "";
    $tipe_pesan = "success";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            if (isset($_POST['form_type']) && $_POST['form_type'] == 'library') {
                
                if ($_POST['aksi'] == 'tambah') {
                    if (!isKategoriValid($_POST['kategori'], $_POST['subjenis'], $validasiMap)) throw new Exception("Kategori tidak valid.");
                    
                    $namaInput = trim($_POST['nama']);
                    // Normalize: remove extra spaces (multiple spaces -> single space)
                    $namaInput = preg_replace('/\s+/', ' ', $namaInput);
                    
                    // Cek duplikat case-insensitive dengan normalisasi spasi
                    $stmtCek = $pdo->prepare("SELECT id FROM libraries WHERE LOWER(TRIM(REPLACE(REPLACE(nama, '  ', ' '), '  ', ' '))) = LOWER(?)");
                    $stmtCek->execute([$namaInput]);
                    if ($stmtCek->rowCount() > 0) throw new Exception("Nama perpustakaan sudah ada (duplikat).");

                    $stmt = $pdo->prepare("INSERT INTO libraries (nama, kategori, jenis) VALUES (?, ?, ?)");
                    $stmt->execute([strtoupper($namaInput), $_POST['kategori'], $_POST['subjenis']]);
                    if(isset($_POST['ajax']) && $_POST['ajax'] == 1) { echo json_encode(['status'=>'success', 'message'=>'Perpustakaan berhasil ditambahkan!']); exit; }
                    $pesan = "Perpustakaan berhasil ditambahkan!";
                } 
                elseif ($_POST['aksi'] == 'edit') {
                    if (!isKategoriValid($_POST['kategori'], $_POST['subjenis'], $validasiMap)) throw new Exception("Kategori tidak valid.");
                    $sql = "UPDATE libraries SET nama=?, kategori=?, jenis=? WHERE id=?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([strtoupper($_POST['nama']), $_POST['kategori'], $_POST['subjenis'], $_POST['id']]);
                    if(isset($_POST['ajax']) && $_POST['ajax'] == 1) { echo json_encode(['status'=>'success', 'message'=>'Data diperbarui!']); exit; }
                    $pesan = "Data diperbarui!";
                } 
                elseif ($_POST['aksi'] == 'hapus') {
                    $stmt = $pdo->prepare("DELETE FROM libraries WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                    if(isset($_POST['ajax']) && $_POST['ajax'] == 1) { echo json_encode(['status'=>'success']); exit; }
                    $pesan = "Perpustakaan dihapus.";
                } 
                elseif ($_POST['aksi'] == 'hapus_bulk') {
                    $ids = $_POST['ids'] ?? [];
                    if (!empty($ids)) {
                        $inQuery = implode(',', array_fill(0, count($ids), '?'));
                        $stmt = $pdo->prepare("DELETE FROM libraries WHERE id IN ($inQuery)");
                        $stmt->execute($ids);
                        echo json_encode(['status'=>'success', 'count'=>count($ids)]);
                    } else {
                        echo json_encode(['status'=>'error', 'message'=>'Tidak ada data dipilih']);
                    }
                    exit;
                } 
                elseif ($_POST['aksi'] == 'reset_status') {
                    $libraryId = (int)($_POST['library_id'] ?? 0);
                    $jenis = strtoupper(trim($_POST['jenis'] ?? ''));
                    $bulan = str_pad((string)($_POST['bulan'] ?? ''), 2, '0', STR_PAD_LEFT);
                    $tahun = (int)($_POST['tahun'] ?? 0);

                    if (!$libraryId || !in_array($jenis, ['IPLM', 'TKM'], true) || !$bulan || !$tahun) {
                        throw new Exception("Parameter reset status tidak valid.");
                    }

                    $pdo->beginTransaction();
                    try {
                        $stmtHeader = $pdo->prepare("SELECT id FROM trans_header WHERE library_id = ? AND jenis_kuesioner = ? AND periode_bulan = ? AND periode_tahun = ?");
                        $stmtHeader->execute([$libraryId, $jenis, $bulan, $tahun]);
                        $headerIds = $stmtHeader->fetchAll(PDO::FETCH_COLUMN);

                        if (!empty($headerIds)) {
                            $inQuery = implode(',', array_fill(0, count($headerIds), '?'));
                            $stmtDelDetail = $pdo->prepare("DELETE FROM trans_detail WHERE header_id IN ($inQuery)");
                            $stmtDelDetail->execute($headerIds);
                            $stmtDelHeader = $pdo->prepare("DELETE FROM trans_header WHERE id IN ($inQuery)");
                            $stmtDelHeader->execute($headerIds);
                        }
                        $pdo->commit();
                    } catch (Exception $e) {
                        $pdo->rollBack();
                        throw $e;
                    }
                    $pesan = "Status perpustakaan berhasil direset menjadi belum mengisi.";
                    if(isset($_POST['ajax']) && $_POST['ajax'] == 1) { echo json_encode(['status'=>'success', 'message'=>$pesan]); exit; }
                }
                elseif ($_POST['aksi'] == 'import_csv') {
                    if (isset($_FILES['file_csv']) && $_FILES['file_csv']['error'] == 0) {
                        $file = $_FILES['file_csv']['tmp_name'];
                        $orig = $_FILES['file_csv']['name'] ?? '';
                        $size = $_FILES['file_csv']['size'] ?? 0;
                        $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));

                        if ($ext !== 'csv') {
                            throw new Exception("File harus berformat .csv");
                        }
                        if ($size > 2 * 1024 * 1024) {
                            throw new Exception("Ukuran file maksimal 2MB.");
                        }
                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $mime = $finfo->file($file);
                        $allowed_mime = ['text/plain', 'text/csv', 'application/csv', 'application/vnd.ms-excel', 'application/octet-stream'];
                        if ($mime && !in_array($mime, $allowed_mime, true)) {
                            throw new Exception("Tipe file tidak valid.");
                        }

                        $handle = fopen($file, "r");
                        
                        // Detect delimiter
                        $firstLine = fgets($handle);
                        $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';
                        rewind($handle);

                        // Remove BOM if present
                        $bom = fread($handle, 3);
                        if ($bom !== "\xEF\xBB\xBF") {
                            rewind($handle);
                        }

                        $header = fgetcsv($handle, 1000, $delimiter);
                        if (!$header || count($header) < 3) {
                            fclose($handle);
                            throw new Exception("Format CSV tidak valid. Harus memiliki minimal 3 kolom: Nama, Jenis, Subjenis.");
                        }
                        
                        $sukses = 0; $gagal = 0;
                        $gagalDetails = [];
                        $stmt = $pdo->prepare("INSERT INTO libraries (nama, kategori, jenis) VALUES (?, ?, ?)");
                        
                        $rowNumber = 1;
                        while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                            $rowNumber++;
                            if (count($row) < 3) { 
                                $gagal++; 
                                $gagalDetails[] = "Baris $rowNumber: Kolom tidak lengkap.";
                                continue; 
                            }
                            $nama = trim($row[0] ?? '');
                            $rawKat = trim($row[1] ?? 'Umum');
                            $rawSub = trim($row[2] ?? '');
                            
                            $nama_ok = ($nama !== '' && mb_strlen($nama) >= 3 && preg_match('/[A-Za-z]/', $nama));
                            if ($nama_ok) {
                                if (isKategoriValid($rawKat, $rawSub, $validasiMap)) {
                                    $keyKat = strtoupper($rawKat);
                                    $keySub = strtoupper($rawSub);
                                    $fixedKat = $formatFixer['KAT'][$keyKat];
                                    $fixedSub = $formatFixer['SUB'][$keySub];

                                    $cek = $pdo->prepare("SELECT id FROM libraries WHERE LOWER(nama) = LOWER(?)");
                                    $cek->execute([$nama]);
                                    if ($cek->rowCount() == 0) {
                                        $stmt->execute([strtoupper($nama), $fixedKat, $fixedSub]);
                                        $sukses++;
                                    } else {
                                        $gagal++;
                                        $gagalDetails[] = "Baris $rowNumber: '$nama' (Sudah ada / Duplikat).";
                                    }
                                } else { 
                                    $gagal++; 
                                    $gagalDetails[] = "Baris $rowNumber: '$nama' (Kategori/Subjenis '$rawKat' - '$rawSub' tidak valid).";
                                }
                            } else { 
                                $gagal++; 
                                $gagalDetails[] = "Baris $rowNumber: '$nama' (Nama tidak valid, min 3 huruf).";
                            }
                        }
                        fclose($handle);
                        
                        $pesan = "Import Selesai. Sukses: <b>$sukses</b>, Gagal: <b>$gagal</b>.";
                        if ($gagal > 0) {
                            $tipe_pesan = ($sukses > 0) ? "warning" : "danger";
                            $pesan .= "<div class='mt-2'><small class='fw-bold'>Detail Data Gagal:</small><ul class='mb-0 small ps-3' style='max-height: 150px; overflow-y: auto;'>";
                            foreach ($gagalDetails as $detail) {
                                $pesan .= "<li>" . htmlspecialchars($detail) . "</li>";
                            }
                            $pesan .= "</ul></div>";
                        }
                    } else { throw new Exception("Gagal upload CSV."); }
                }
            }
            elseif (isset($_POST['form_type']) && $_POST['form_type'] == 'category') {
                if ($_POST['aksi'] == 'tambah') {
                    $kategoriInput = trim($_POST['kategori']);
                    $subInput = trim($_POST['sub_kategori']);

                    // Cek duplikat case-insensitive
                    $stmtCek = $pdo->prepare("SELECT id FROM master_kategori WHERE LOWER(kategori) = LOWER(?) AND LOWER(sub_kategori) = LOWER(?)");
                    $stmtCek->execute([$kategoriInput, $subInput]);
                    if ($stmtCek->rowCount() > 0) throw new Exception("Kategori/Sub Jenis tersebut sudah ada.");

                    $stmt = $pdo->prepare("INSERT INTO master_kategori (kategori, sub_kategori) VALUES (?, ?)");
                    
                    // Kategori: Title Case
                    $kategoriSave = ucwords(strtolower($kategoriInput));
                    
                    // Sub Jenis: "Perpustakaan" Capitalized, sisanya sesuai input user
                    $words = explode(' ', $subInput);
                    foreach ($words as &$w) {
                        if (strtolower($w) === 'perpustakaan') {
                            $w = 'Perpustakaan';
                        }
                    }
                    $subSave = implode(' ', $words);
                    
                    $stmt->execute([$kategoriSave, $subSave]);
                    $pesan = "Kategori ditambahkan!";
                } elseif ($_POST['aksi'] == 'hapus') {
                    $stmt = $pdo->prepare("DELETE FROM master_kategori WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                    $pesan = "Kategori dihapus.";
                }

                $_SESSION['flash_message'] = $pesan;
                $_SESSION['flash_type'] = $tipe_pesan;
                $this->redirect('/admin/perpustakaan?tab=category'); return;
            }
            $_SESSION['flash_message'] = $pesan;
            $_SESSION['flash_type'] = $tipe_pesan;
            $this->redirect('/admin/perpustakaan'); return;
        } catch (Exception $e) {
            if(isset($_POST['ajax']) && $_POST['ajax'] == 1) { echo json_encode(['status'=>'error', 'message'=>'Terjadi kesalahan server.']); exit; }
            $pesan = "Terjadi kesalahan. Silakan coba lagi.";
            $tipe_pesan = "danger";
        }
    }

    if (isset($_SESSION['flash_message'])) { 
        $pesan = $_SESSION['flash_message']; 
        $tipe_pesan = $_SESSION['flash_type'] ?? 'success';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
    }

    // Generate List Bulan & Tahun untuk Filter
    $bulanList = [
        '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
        '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
        '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
    ];
    $tahunIni = date('Y');

    // --- RINCIAN STATISTIK DATA (BULANAN) ---
    $perpus_filter = $_SESSION['perpustakaan_filter'] ?? [];
    $bulan_pilih = $perpus_filter['bulan'] ?? date('m');
    $tahun_pilih = $perpus_filter['tahun'] ?? date('Y');
    $label_periode = $bulanList[$bulan_pilih] . " " . $tahun_pilih;

    try { 
        $stmt = $pdo->query("SELECT COUNT(*) FROM libraries"); 
        $total_perpus = $stmt->fetchColumn(); 
    } catch (Exception $e) { $total_perpus = 0; }

    try {
        $stmtIplm = $pdo->prepare("SELECT COUNT(*) FROM trans_header WHERE jenis_kuesioner = 'IPLM' AND periode_bulan = :bln AND periode_tahun = :thn");
        $stmtIplm->execute([':bln' => $bulan_pilih, ':thn' => $tahun_pilih]); 
        $total_iplm = $stmtIplm->fetchColumn();
    } catch (Exception $e) { $total_iplm = 0; }

    $belum_iplm = max(0, $total_perpus - $total_iplm);
    // END STRIPPED LOGIC

        $viewData = compact('rawKat', 'strukturJenis', 'validasiMap', 'formatFixer', 'pesan', 'tipe_pesan', 'bulanList', 'tahunIni', 'perpus_filter', 'bulan_pilih', 'tahun_pilih', 'label_periode', 'total_perpus', 'total_iplm', 'belum_iplm');
        $this->view('admin/perpustakaan', $viewData);
    }

    public function hasil_kuisioner() {
        $this->requireAuth();
        global $pdo;

        date_default_timezone_set('Asia/Makassar');
        $list_bulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') === 'hapus_header') {
            $headerId = (int)($_POST['header_id'] ?? 0);
            if ($headerId > 0) {
                $pdo->beginTransaction();
                try {
                    $stmtDelDetail = $pdo->prepare("DELETE FROM trans_detail WHERE header_id = ?");
                    $stmtDelDetail->execute([$headerId]);
                    $stmtDelHeader = $pdo->prepare("DELETE FROM trans_header WHERE id = ?");
                    $stmtDelHeader->execute([$headerId]);
                    $pdo->commit();
                } catch (Exception $e) {
                    $pdo->rollBack();
                }
            }
            $this->redirect('/admin/hasil_kuisioner');
            return;
        }

        $filter_keys = ['jenis','start_bulan','start_tahun','end_bulan','end_tahun'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') !== 'hapus_header') {
            $session_filter = [];
            foreach ($filter_keys as $k) {
                if (isset($_POST[$k])) $session_filter[$k] = $_POST[$k];
            }
            $_SESSION['hasil_kuisioner_filter'] = $session_filter;
            $this->redirect('/admin/hasil_kuisioner');
            return;
        }

        if (!empty($_GET)) {
            $session_filter = [];
            foreach ($filter_keys as $k) {
                if (isset($_GET[$k])) $session_filter[$k] = $_GET[$k];
            }
            if (!empty($session_filter)) {
                $_SESSION['hasil_kuisioner_filter'] = array_merge($_SESSION['hasil_kuisioner_filter'] ?? [], $session_filter);
                $this->redirect('/admin/hasil_kuisioner');
                return;
            }
        }

        $filter = $_SESSION['hasil_kuisioner_filter'] ?? [];

        $jenis = isset($filter['jenis']) ? strtolower($filter['jenis']) : 'iplm';
        if (!in_array($jenis, ['iplm', 'tkm'])) $jenis = 'iplm';

        $start_bln = isset($filter['start_bulan']) ? str_pad($filter['start_bulan'], 2, '0', STR_PAD_LEFT) : '01';
        $start_thn = isset($filter['start_tahun']) ? (int)$filter['start_tahun'] : (int)date('Y');
        $end_bln   = isset($filter['end_bulan']) ? str_pad($filter['end_bulan'], 2, '0', STR_PAD_LEFT) : date('m');
        $end_thn   = isset($filter['end_tahun']) ? (int)$filter['end_tahun'] : (int)date('Y');

        $start_key = sprintf('%04d-%02d', $start_thn, (int)$start_bln);
        $end_key   = sprintf('%04d-%02d', $end_thn, (int)$end_bln);
        if ($start_key > $end_key) {
            $tmp = $start_bln; $start_bln = $end_bln; $end_bln = $tmp;
            $tmp = $start_thn; $start_thn = $end_thn; $end_thn = $tmp;
            $start_key = sprintf('%04d-%02d', $start_thn, (int)$start_bln);
            $end_key   = sprintf('%04d-%02d', $end_thn, (int)$end_bln);
        }

        $jenis_upper = strtoupper($jenis);
        $start_period = (int)($start_thn . $start_bln);
        $end_period   = (int)($end_thn . $end_bln);

        $stmtSoal = $pdo->prepare("SELECT id, teks_pertanyaan, kategori_bagian, tipe_input FROM master_pertanyaan WHERE jenis_kuesioner = ? ORDER BY kategori_bagian ASC, urutan ASC");
        $stmtSoal->execute([$jenis_upper]);
        $daftar_soal = $stmtSoal->fetchAll(PDO::FETCH_ASSOC);

        $sql = "SELECT h.id as header_id, h.periode_bulan, h.periode_tahun,
                       l.nama as nama_perpus, l.jenis as jenis_perpus, l.kategori
                FROM trans_header h
                LEFT JOIN libraries l ON h.library_id = l.id
                WHERE h.jenis_kuesioner = :jenis
                AND (CAST(CONCAT(h.periode_tahun, h.periode_bulan) AS UNSIGNED) >= :start_p)
                AND (CAST(CONCAT(h.periode_tahun, h.periode_bulan) AS UNSIGNED) <= :end_p)
                ORDER BY h.id ASC";
        $stmtData = $pdo->prepare($sql);
        $stmtData->execute([
            ':jenis'   => $jenis_upper,
            ':start_p' => $start_period,
            ':end_p'   => $end_period
        ]);
        $responden = $stmtData->fetchAll(PDO::FETCH_ASSOC);

        $jawaban_map = [];
        $list_header_ids = array_column($responden, 'header_id');
        if (!empty($list_header_ids)) {
            $inQuery = implode(',', array_fill(0, count($list_header_ids), '?'));
            $stmtDetail = $pdo->prepare("SELECT header_id, pertanyaan_id, jawaban FROM trans_detail WHERE header_id IN ($inQuery)");
            $stmtDetail->execute($list_header_ids);
            while ($row = $stmtDetail->fetch(PDO::FETCH_ASSOC)) {
                $jawaban_map[$row['header_id']][$row['pertanyaan_id']] = $row['jawaban'];
            }
        }

        $likert_map = [
            '1' => 'Sangat Tidak Setuju',
            '2' => 'Tidak Setuju',
            '3' => 'Setuju',
            '4' => 'Sangat Setuju'
        ];

        $periode_label = ($start_key === $end_key)
            ? ($list_bulan[$start_bln] ?? $start_bln) . " " . $start_thn
            : ($list_bulan[$start_bln] ?? $start_bln) . " " . $start_thn . " - " . ($list_bulan[$end_bln] ?? $end_bln) . " " . $end_thn;

        $this->view('admin/hasil_kuisioner', [
            'list_bulan' => $list_bulan,
            'jenis' => $jenis,
            'start_bln' => $start_bln,
            'start_thn' => $start_thn,
            'end_bln' => $end_bln,
            'end_thn' => $end_thn,
            'daftar_soal' => $daftar_soal,
            'responden' => $responden,
            'jawaban_map' => $jawaban_map,
            'likert_map' => $likert_map,
            'periode_label' => $periode_label
        ]);
    }

    public function export_data() {
        $this->requireAuth();
        global $pdo;

        require_once BASE_PATH . 'vendor/autoload.php';

        // Set Zona Waktu
        date_default_timezone_set('Asia/Makassar');

        $filter = $_SESSION['hasil_kuisioner_filter'] ?? [];
        $jenis = $_POST['jenis'] ?? ($filter['jenis'] ?? ($_GET['jenis'] ?? ''));
        $start_bln = $_POST['start_bulan'] ?? ($filter['start_bulan'] ?? ($_GET['start_bulan'] ?? date('m')));
        $start_thn = $_POST['start_tahun'] ?? ($filter['start_tahun'] ?? ($_GET['start_tahun'] ?? date('Y')));
        $end_bln   = $_POST['end_bulan'] ?? ($filter['end_bulan'] ?? ($_GET['end_bulan'] ?? date('m')));
        $end_thn   = $_POST['end_tahun'] ?? ($filter['end_tahun'] ?? ($_GET['end_tahun'] ?? date('Y')));
        $status_filter = $_POST['status_filter'] ?? 'filled';

        $start_bln = str_pad($start_bln, 2, '0', STR_PAD_LEFT);
        $end_bln = str_pad($end_bln, 2, '0', STR_PAD_LEFT);

        if (!in_array($jenis, ['iplm', 'tkm'])) {
            die("Error: Jenis laporan tidak valid.");
        }

        $timestamp = date('Ymd_His');
        $filename = "Rekap_{$jenis}_{$start_bln}{$start_thn}_sd_{$end_bln}{$end_thn}.xlsx"; 
        
        $upper_str = function($value) {
            if (!is_string($value)) return $value;
            if (function_exists('mb_strtoupper')) return mb_strtoupper($value, 'UTF-8');
            return strtoupper($value);
        };
        
        $title_text = $upper_str("Rekapitulasi Data " . $jenis);
        $periode_text = $upper_str("Periode: $start_bln/$start_thn s.d. $end_bln/$end_thn");

        $stmtSoal = $pdo->prepare("SELECT id, teks_pertanyaan, kategori_bagian, tipe_input FROM master_pertanyaan WHERE jenis_kuesioner = ? ORDER BY kategori_bagian ASC, urutan ASC");
        $stmtSoal->execute([strtoupper($jenis)]);
        $daftar_soal = $stmtSoal->fetchAll(PDO::FETCH_ASSOC);

        $start_period = (int)($start_thn . $start_bln);
        $end_period   = (int)($end_thn . $end_bln);

        $sql = "SELECT h.id as header_id, h.periode_bulan, h.periode_tahun, h.library_id,
                       l.nama as nama_perpus, l.jenis as jenis_perpus, l.kategori
                FROM trans_header h
                LEFT JOIN libraries l ON h.library_id = l.id
                WHERE h.jenis_kuesioner = :jenis
                AND (CAST(CONCAT(h.periode_tahun, h.periode_bulan) AS UNSIGNED) >= :start_p)
                AND (CAST(CONCAT(h.periode_tahun, h.periode_bulan) AS UNSIGNED) <= :end_p)
                ORDER BY h.id ASC";

        $stmtData = $pdo->prepare($sql);
        $stmtData->execute([
            ':jenis'   => strtoupper($jenis),
            ':start_p' => $start_period,
            ':end_p'   => $end_period
        ]);
        $responden = $stmtData->fetchAll(PDO::FETCH_ASSOC);

        if ($jenis === 'iplm' && in_array($status_filter, ['unfilled', 'all'])) {
            $stmtLib = $pdo->query("SELECT id, nama, kategori, jenis FROM libraries ORDER BY nama ASC");
            $all_libs = $stmtLib->fetchAll(PDO::FETCH_ASSOC);

            $periods = [];
            $curr_ts = strtotime("$start_thn-$start_bln-01");
            $end_ts  = strtotime("$end_thn-$end_bln-01");
            
            while ($curr_ts <= $end_ts) {
                $p_month = date('m', $curr_ts);
                $p_year  = date('Y', $curr_ts);
                $periods[] = ['m' => $p_month, 'y' => $p_year];
                $curr_ts = strtotime("+1 month", $curr_ts);
            }

            $existing_map = [];
            foreach ($responden as $r) {
                $key = $r['periode_tahun'] . $r['periode_bulan'];
                $existing_map[$key][$r['library_id']] = $r;
            }

            $final_rows = [];
            foreach ($all_libs as $lib) {
                foreach ($periods as $p) {
                    $p_key = $p['y'] . $p['m'];
                    $is_filled = isset($existing_map[$p_key][$lib['id']]);
                    
                    if ($status_filter === 'unfilled' && $is_filled) continue;
                    if ($status_filter === 'filled' && !$is_filled) continue;

                    if ($is_filled) {
                        $final_rows[] = $existing_map[$p_key][$lib['id']];
                    } else {
                        $final_rows[] = [
                            'header_id' => null, 
                            'periode_bulan' => $p['m'],
                            'periode_tahun' => $p['y'],
                            'library_id' => $lib['id'],
                            'nama_perpus' => $lib['nama'],
                            'jenis_perpus' => $lib['jenis'],
                            'kategori' => $lib['kategori']
                        ];
                    }
                }
            }
            $responden = $final_rows;
        }

        $all_header_ids = array_column($responden, 'header_id');
        $list_header_ids = array_filter($all_header_ids, function($v) { return !is_null($v) && $v > 0; });

        $jawaban_map = [];

        if (!empty($list_header_ids)) {
            $inQuery = implode(',', array_fill(0, count($list_header_ids), '?'));
            $stmtDetail = $pdo->prepare("SELECT header_id, pertanyaan_id, jawaban FROM trans_detail WHERE header_id IN ($inQuery)");
            $stmtDetail->execute(array_values($list_header_ids));
            while ($row = $stmtDetail->fetch(PDO::FETCH_ASSOC)) {
                $jawaban_map[$row['header_id']][$row['pertanyaan_id']] = $row['jawaban'];
            }
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $grouped_responden = [];
        if ($jenis === 'iplm') {
            foreach ($responden as $row) {
                $kat = !empty($row['kategori']) ? strtoupper($row['kategori']) : 'LAINNYA';
                $grouped_responden[$kat][] = $row;
            }
            ksort($grouped_responden);
        } else {
            $grouped_responden['DATA'] = $responden;
        }

        $sheetIndex = 0;
        foreach ($grouped_responden as $sheetTitle => $sheetData) {
            if (empty($sheetData) && count($grouped_responden) > 1) continue;

            if ($sheetIndex === 0) {
                $sheet = $spreadsheet->getActiveSheet();
            } else {
                $sheet = $spreadsheet->createSheet($sheetIndex);
            }
            
            $safeTitle = substr($sheetTitle, 0, 31);
            $safeTitle = str_replace(['*', ':', '/', '\\', '?', '[', ']'], '', $safeTitle);
            $sheet->setTitle($safeTitle ?: 'Sheet'.$sheetIndex);

            $sheet->setCellValue('A1', $title_text . " - " . $sheetTitle);
            $sheet->setCellValue('A2', $periode_text);
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
            $sheet->getStyle('A2')->getFont()->setItalic(true);

            $row_head_1 = 4;
            $row_head_2 = 5;

            $sheet->setCellValue('A'.$row_head_1, $upper_str('NO')); $sheet->mergeCells("A$row_head_1:A$row_head_2");
            $sheet->setCellValue('B'.$row_head_1, $upper_str('PERIODE')); $sheet->mergeCells("B$row_head_1:B$row_head_2");
            
            $col = 'C'; 
            if ($jenis == 'iplm') {
                $sheet->setCellValue($col.$row_head_1, $upper_str('NAMA PERPUSTAKAAN')); $sheet->mergeCells("{$col}{$row_head_1}:{$col}{$row_head_2}"); 
                $sheet->getColumnDimension($col)->setWidth(30); 
                $col++;
                
                $sheet->setCellValue($col.$row_head_1, $upper_str('KATEGORI')); $sheet->mergeCells("{$col}{$row_head_1}:{$col}{$row_head_2}"); $col++;
                $sheet->setCellValue($col.$row_head_1, $upper_str('JENIS')); $sheet->mergeCells("{$col}{$row_head_1}:{$col}{$row_head_2}"); $col++;
            }

            $grouped_soal = [];
            foreach ($daftar_soal as $s) {
                $bag = $s['kategori_bagian'] ?: 'LAINNYA';
                $grouped_soal[$bag][] = $s;
            }

            $colors = ['FFFFE0B2', 'FFC8E6C9', 'FFBBDEFB', 'FFF8BBD0', 'FFE1BEE7']; 
            $color_idx = 0;

            foreach ($grouped_soal as $kategori => $items) {
                $jml_soal = count($items);
                
                $start_col = $col;
                for ($i = 1; $i < $jml_soal; $i++) $col++; 
                $end_col = $col;
                
                $sheet->setCellValue($start_col.$row_head_1, $upper_str($kategori));
                if($start_col != $end_col) {
                    $sheet->mergeCells("$start_col$row_head_1:$end_col$row_head_1");
                }
                
                $bg_color = $colors[$color_idx % count($colors)];
                $sheet->getStyle("$start_col$row_head_1:$end_col$row_head_1")->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($bg_color);
                
                $curr = $start_col;
                foreach ($items as $item) {
                    $sheet->setCellValue($curr.$row_head_2, $upper_str($item['teks_pertanyaan']));
                    $sheet->getColumnDimension($curr)->setWidth(20);
                    $curr++;
                }
                
                $col++;
                $color_idx++;
            }
            $last_col = $sheet->getHighestColumn();

            $header_style = [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
                ]
            ];
            $sheet->getStyle("A$row_head_1:$last_col$row_head_2")->applyFromArray($header_style);

            $row_num = $row_head_2 + 1;
            $no = 1;

            $likert_map = [
                '1' => $upper_str('Sangat Tidak Setuju'),
                '2' => $upper_str('Tidak Setuju'),
                '3' => $upper_str('Setuju'),
                '4' => $upper_str('Sangat Setuju')
            ];

            if (empty($sheetData)) {
                $sheet->setCellValue('A'.$row_num, $upper_str('Tidak ada data pada periode ini.'));
                $sheet->mergeCells("A$row_num:$last_col$row_num");
                $sheet->getStyle("A$row_num")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            } else {
                foreach ($sheetData as $row) {
                    $sheet->setCellValue('A'.$row_num, $no++);
                    $sheet->setCellValue('B'.$row_num, $row['periode_bulan'] . '/' . $row['periode_tahun']);
                    
                    $col = 'C'; 
                    if ($jenis == 'iplm') {
                        $sheet->setCellValue($col++.$row_num, $upper_str($row['nama_perpus'] ?? '-'));
                        $sheet->setCellValue($col++.$row_num, $upper_str($row['kategori'] ?? '-'));
                        $sheet->setCellValue($col++.$row_num, $upper_str($row['jenis_perpus'] ?? '-'));
                    }

                    foreach ($daftar_soal as $s) {
                        $id_soal = $s['id'];
                        $val = isset($jawaban_map[$row['header_id']][$id_soal]) ? $jawaban_map[$row['header_id']][$id_soal] : '-';
                        
                        if ($s['tipe_input'] == 'likert' && isset($likert_map[$val])) {
                            $val = $likert_map[$val];
                        }
                        
                        $sheet->setCellValueExplicit($col++.$row_num, $upper_str((string)$val), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    }
                    $row_num++;
                }
            }

            $last_row = $row_num - 1;
            if ($last_row >= $row_head_1) {
                $sheet->getStyle("A$row_head_1:$last_col$last_row")->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle("A$row_head_1:B$last_row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); 
            }

            $sheetIndex++;
        }

        $spreadsheet->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }


public function atur_pertanyaan() {
// web-perpus-v1/admin/atur_pertanyaan.php
$this->requireAuth();
global $pdo;


// Pastikan tabel kategori_bagian ada (untuk manajemen bagian/label)
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS kategori_bagian (
        id INT AUTO_INCREMENT PRIMARY KEY,
        jenis_kuesioner VARCHAR(10) NOT NULL,
        name VARCHAR(255) NOT NULL,
        position INT NOT NULL DEFAULT 0,
        numbering_style ENUM('numeric','roman','none') NOT NULL DEFAULT 'numeric',
        manual_label VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Migrasi otomatis kategori yang sudah ada jika tabel masih kosong
    $stmtChk = $pdo->query("SELECT COUNT(*) FROM kategori_bagian");
    $exists = (int)$stmtChk->fetchColumn();
    if ($exists === 0) {
        $stmtDistinct = $pdo->query("SELECT jenis_kuesioner, kategori_bagian, MIN(id) AS first_id FROM master_pertanyaan GROUP BY jenis_kuesioner, kategori_bagian ORDER BY jenis_kuesioner, MIN(id)");
        $rows = $stmtDistinct->fetchAll(PDO::FETCH_ASSOC);
        $stmtIns = $pdo->prepare("INSERT INTO kategori_bagian (jenis_kuesioner, name, position, numbering_style) VALUES (?, ?, ?, 'numeric')");
        $lastJenis = null;
        $pos = 1;
        foreach ($rows as $r) {
            if ($r['jenis_kuesioner'] !== $lastJenis) {
                $pos = 1;
                $lastJenis = $r['jenis_kuesioner'];
            }
            $stmtIns->execute([$r['jenis_kuesioner'], $r['kategori_bagian'], $pos]);
            $pos++;
        }
    } else {
        // Sync missing categories (jika ada kategori baru di master_pertanyaan tapi belum ada di kategori_bagian)
        $sqlMiss = "SELECT DISTINCT m.jenis_kuesioner, m.kategori_bagian 
                    FROM master_pertanyaan m 
                    LEFT JOIN kategori_bagian k ON k.jenis_kuesioner = m.jenis_kuesioner AND k.name = m.kategori_bagian
                    WHERE k.id IS NULL AND m.kategori_bagian IS NOT NULL AND m.kategori_bagian <> ''";
        $stmtMiss = $pdo->query($sqlMiss);
        $missing = $stmtMiss->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($missing)) {
            $stmtMaxPosKB = $pdo->prepare("SELECT COALESCE(MAX(position), 0) FROM kategori_bagian WHERE jenis_kuesioner = ?");
            $stmtInsKB = $pdo->prepare("INSERT INTO kategori_bagian (jenis_kuesioner, name, position) VALUES (?, ?, ?)");
            
            foreach ($missing as $row) {
                $j = $row['jenis_kuesioner'];
                $n = $row['kategori_bagian'];
                
                $stmtMaxPosKB->execute([$j]);
                $pos = (int)$stmtMaxPosKB->fetchColumn() + 1;
                
                $stmtInsKB->execute([$j, $n, $pos]);
            }
        }
    }
} catch (Exception $e) {
    // jangan gagalkan halaman jika pembuatan tabel/migrasi gagal
}

if (!empty($_GET) && (isset($_GET['tab']) || isset($_GET['page_iplm']) || isset($_GET['page_tkm']))) {
    $_SESSION['atur_pertanyaan_state'] = [
        'tab' => $_GET['tab'] ?? 'iplm',
        'page_iplm' => (int)($_GET['page_iplm'] ?? 1),
        'page_tkm' => (int)($_GET['page_tkm'] ?? 1),
    ];
    header("Location: atur_pertanyaan.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nav_only'])) {
    $_SESSION['atur_pertanyaan_state'] = [
        'tab' => $_POST['tab'] ?? 'iplm',
        'page_iplm' => max(1, (int)($_POST['page_iplm'] ?? 1)),
        'page_tkm' => max(1, (int)($_POST['page_tkm'] ?? 1)),
    ];
    header("Location: atur_pertanyaan.php");
    exit;
}

// --- 1. PROSES CRUD ---
$pesan = "";
$pesan_type = "success"; // success atau danger
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['aksi']) && $_POST['aksi'] === 'import_csv') {
            if (!isset($_FILES['file_csv']) || $_FILES['file_csv']['error'] !== 0) {
                throw new Exception("Gagal upload CSV.");
            }

            $file = $_FILES['file_csv']['tmp_name'];
            $orig = $_FILES['file_csv']['name'] ?? '';
            $size = $_FILES['file_csv']['size'] ?? 0;
            $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
            if ($ext !== 'csv') {
                throw new Exception("File harus berformat .csv");
            }
            if ($size > 2 * 1024 * 1024) {
                throw new Exception("Ukuran file maksimal 2MB.");
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($file);
            $allowed_mime = ['text/plain', 'text/csv', 'application/csv', 'application/vnd.ms-excel'];
            if ($mime && !in_array($mime, $allowed_mime, true)) {
                throw new Exception("Tipe file tidak valid.");
            }

            $handle = fopen($file, "r");
            if (!$handle) throw new Exception("File CSV tidak bisa dibuka.");

            $header = fgetcsv($handle);
            if (!$header) {
                fclose($handle);
                throw new Exception("CSV kosong.");
            }

            $header_map = [];
            $lower = array_map(function($h){ return strtolower(trim($h)); }, $header);
            $has_header = in_array('jenis_kuesioner', $lower, true) || in_array('jenis', $lower, true);
            if ($has_header) {
                foreach ($lower as $i => $h) {
                    $header_map[$h] = $i;
                }
            } else {
                rewind($handle);
            }

            $allowed_tipe = ['text','number','textarea','likert','select','radio'];
            $count_cache = [];
            $sukses = 0; $gagal = 0;

            $stmtInsert = $pdo->prepare("INSERT INTO master_pertanyaan (jenis_kuesioner, kategori_bagian, teks_pertanyaan, keterangan, tipe_input, pilihan_opsi, urutan) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtShift = $pdo->prepare("UPDATE master_pertanyaan SET urutan = urutan + 1 WHERE jenis_kuesioner = ? AND kategori_bagian = ? AND CAST(urutan AS UNSIGNED) >= ?");

            while (($row = fgetcsv($handle, 2000, ",")) !== false) {
                if ($has_header) {
                    $jenis = $row[$header_map['jenis_kuesioner'] ?? $header_map['jenis'] ?? -1] ?? '';
                    $bagian = $row[$header_map['kategori_bagian'] ?? $header_map['kategori'] ?? -1] ?? '';
                    $soal = $row[$header_map['teks_pertanyaan'] ?? $header_map['pertanyaan'] ?? -1] ?? '';
                    $keterangan = $row[$header_map['keterangan'] ?? -1] ?? '';
                    $tipe = $row[$header_map['tipe_input'] ?? $header_map['tipe'] ?? -1] ?? '';
                    $pilihan_opsi = $row[$header_map['pilihan_opsi'] ?? $header_map['opsi'] ?? -1] ?? '';
                    $urutan = $row[$header_map['urutan'] ?? -1] ?? '';
                } else {
                    $jenis = $row[0] ?? '';
                    $bagian = $row[1] ?? '';
                    $soal = $row[2] ?? '';
                    $keterangan = $row[3] ?? '';
                    $tipe = $row[4] ?? '';
                    $pilihan_opsi = $row[5] ?? '';
                    $urutan = $row[6] ?? '';
                }

                $jenis = strtoupper(trim((string)$jenis));
                $bagian = trim((string)$bagian);
                $soal = trim((string)$soal);
                $keterangan = trim((string)$keterangan);
                $tipe = strtolower(trim((string)$tipe));
                $pilihan_opsi = trim((string)$pilihan_opsi);
                $urutan = (int)$urutan;

                if (!in_array($jenis, ['IPLM','TKM'], true) || $bagian === '' || $soal === '') {
                    $gagal++; 
                    continue;
                }
                if (!in_array($tipe, $allowed_tipe, true)) $tipe = 'text';

                // AUTO-CREATE KATEGORI BAGIAN IF NOT EXISTS
                $stmtCheckBagian = $pdo->prepare("SELECT id FROM kategori_bagian WHERE jenis_kuesioner = ? AND name = ? LIMIT 1");
                $stmtCheckBagian->execute([$jenis, $bagian]);
                if (!$stmtCheckBagian->fetch()) {
                    $stmtMaxPosKB = $pdo->prepare("SELECT COALESCE(MAX(position), 0) FROM kategori_bagian WHERE jenis_kuesioner = ?");
                    $stmtMaxPosKB->execute([$jenis]);
                    $newPosKB = (int)$stmtMaxPosKB->fetchColumn() + 1;
                    
                    $stmtInsKB = $pdo->prepare("INSERT INTO kategori_bagian (jenis_kuesioner, name, position) VALUES (?, ?, ?)");
                    $stmtInsKB->execute([$jenis, $bagian, $newPosKB]);
                }

                $key = $jenis . '|' . $bagian;
                if (!isset($count_cache[$key])) {
                    $stmtMax = $pdo->prepare("SELECT MAX(CAST(urutan AS UNSIGNED)) FROM master_pertanyaan WHERE jenis_kuesioner = ? AND kategori_bagian = ?");
                    $stmtMax->execute([$jenis, $bagian]);
                    $count_cache[$key] = (int)$stmtMax->fetchColumn();
                }
                $currentMax = (int)$count_cache[$key];
                $maxPos = $currentMax + 1;
                
                if ($urutan < 1) $urutan = $maxPos;
                if ($urutan > $maxPos) $urutan = $maxPos;

                try {
                    // geser urutan agar tidak duplikasi
                    $stmtShift->execute([$jenis, $bagian, $urutan]);
                    $stmtInsert->execute([$jenis, $bagian, $soal, $keterangan, $tipe, $pilihan_opsi, $urutan]);
                    $count_cache[$key] = max($count_cache[$key], $urutan);
                    $sukses++;
                } catch (Exception $e) {
                    $gagal++;
                }
            }
            fclose($handle);

            $_SESSION['flash_message'] = "Import selesai. Berhasil: $sukses. Gagal: $gagal.";
            header("Location: atur_pertanyaan.php"); exit;
        }

        // CSV Import Logic Removed

        if (isset($_POST['aksi']) && $_POST['aksi'] === 'bulk_delete') {
            $ids = $_POST['ids'] ?? [];
            if (!empty($ids) && is_array($ids)) {
                $ids = array_map('intval', $ids);
                $ids = array_filter($ids);
                
                if (!empty($ids)) {
                    $inQuery = implode(',', array_fill(0, count($ids), '?'));
                    $pdo->beginTransaction();
                    try {
                        $stmt = $pdo->prepare("DELETE FROM master_pertanyaan WHERE id IN ($inQuery)");
                        $stmt->execute($ids);
                        $pdo->commit();
                        $_SESSION['flash_message'] = count($ids) . " pertanyaan terpilih berhasil dihapus.";
                    } catch (Exception $e) {
                         if ($pdo->inTransaction()) $pdo->rollBack();
                         throw $e;
                    }
                }
            }
            $redirectTab = $_POST['tab'] ?? 'iplm';
            $redirectPageIplm = (int)($_POST['page_iplm'] ?? 1);
            $redirectPageTkm = (int)($_POST['page_tkm'] ?? 1);
            $_SESSION['atur_pertanyaan_state'] = [
                'tab' => $redirectTab,
                'page_iplm' => max(1, $redirectPageIplm),
                'page_tkm' => max(1, $redirectPageTkm),
            ];
            header("Location: atur_pertanyaan.php"); exit;
        }

        if (isset($_POST['aksi']) && $_POST['aksi'] === 'set_kontak_iplm') {
            $kontak_id = (int)($_POST['kontak_pertanyaan_id'] ?? 0);
            $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('iplm_kontak_pertanyaan_id', ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
            $stmt->execute([$kontak_id]);
            $_SESSION['flash_message'] = "Kontak IPLM berhasil diperbarui.";
            $redirectTab = 'iplm';
            $redirectPageIplm = (int)($_POST['page_iplm'] ?? 1);
            $redirectPageTkm = (int)($_POST['page_tkm'] ?? 1);
            $_SESSION['atur_pertanyaan_state'] = [
                'tab' => $redirectTab,
                'page_iplm' => max(1, $redirectPageIplm),
                'page_tkm' => max(1, $redirectPageTkm),
            ];
            header("Location: atur_pertanyaan.php"); exit;
        }
        if (isset($_POST['aksi']) && $_POST['aksi'] === 'set_autofill_iplm') {
            $id_jenis = (int)($_POST['autofill_jenis_id'] ?? 0);
            $id_subjenis = (int)($_POST['autofill_subjenis_id'] ?? 0);
            $id_nama = (int)($_POST['autofill_nama_id'] ?? 0);

            $pairs = [
                'iplm_autofill_jenis_id' => $id_jenis,
                'iplm_autofill_subjenis_id' => $id_subjenis,
                'iplm_autofill_nama_id' => $id_nama,
            ];
            $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
            foreach ($pairs as $key => $val) {
                $stmt->execute([$key, $val]);
            }

            $_SESSION['flash_message'] = "Auto-fill IPLM berhasil diperbarui.";
            $redirectTab = 'iplm';
            $redirectPageIplm = (int)($_POST['page_iplm'] ?? 1);
            $redirectPageTkm = (int)($_POST['page_tkm'] ?? 1);
            $_SESSION['atur_pertanyaan_state'] = [
                'tab' => $redirectTab,
                'page_iplm' => max(1, $redirectPageIplm),
                'page_tkm' => max(1, $redirectPageTkm),
            ];
            header("Location: atur_pertanyaan.php"); exit;
        }

        // --- Manajemen Bagian (kategori_bagian) ---
        if (isset($_POST['aksi']) && $_POST['aksi'] === 'add_bagian') {
            $jb = $_POST['jenis_bagian'] ?? 'IPLM';
            $name = trim($_POST['nama_bagian'] ?? '');
            if ($name === '') throw new Exception('Nama bagian tidak boleh kosong');
            // FIX: Filter position by jenis_kuesioner untuk domain isolation
            $stmtPos = $pdo->prepare("SELECT COALESCE(MAX(position),0) FROM kategori_bagian WHERE jenis_kuesioner = ?");
            $stmtPos->execute([$jb]);
            $pos = (int)$stmtPos->fetchColumn() + 1;
            // Hapus numbering_style dari INSERT - gunakan setting global
            $stmtIns = $pdo->prepare("INSERT INTO kategori_bagian (jenis_kuesioner, name, position) VALUES (?, ?, ?)");
            $stmtIns->execute([$jb, $name, $pos]);
            $_SESSION['flash_message'] = 'Bagian baru berhasil ditambahkan.';
            header('Location: atur_pertanyaan.php'); exit;
        }

        if (isset($_POST['aksi']) && $_POST['aksi'] === 'edit_bagian') {
            $id = (int)($_POST['bagian_id'] ?? 0);
            $newName = trim($_POST['nama_bagian'] ?? '');
            $targetPos = (int)($_POST['posisi_bagian'] ?? 0);
            
            if ($id <= 0) throw new Exception('ID bagian tidak valid');
            if ($newName === '') throw new Exception('Nama bagian tidak boleh kosong');
            if ($targetPos < 1) $targetPos = 1;

            $pdo->beginTransaction();
            try {
                // 1. Ambil data item yang diedit
                $stmtGet = $pdo->prepare("SELECT jenis_kuesioner, name FROM kategori_bagian WHERE id = ?");
                $stmtGet->execute([$id]);
                $current = $stmtGet->fetch(PDO::FETCH_ASSOC);
                if (!$current) throw new Exception('Bagian tidak ditemukan');
                
                $jenis = $current['jenis_kuesioner'];
                $stmtMaxPos = $pdo->prepare("SELECT COUNT(*) FROM kategori_bagian WHERE jenis_kuesioner = ?");
$stmtMaxPos->execute([$jenis]);
$maxPos = (int)$stmtMaxPos->fetchColumn();

if ($targetPos > $maxPos) {
    $targetPos = $maxPos;
}

                $oldName = $current['name'];

                // 2. Update Nama (jika berubah)
                if ($newName !== $oldName) {
                    $stmtUpName = $pdo->prepare("UPDATE kategori_bagian SET name = ? WHERE id = ?");
                    $stmtUpName->execute([$newName, $id]);
                    
                    // Update referensi di master_pertanyaan
                    $stmtUpRef = $pdo->prepare("UPDATE master_pertanyaan SET kategori_bagian = ? WHERE jenis_kuesioner = ? AND kategori_bagian = ?");
                    $stmtUpRef->execute([$newName, $jenis, $oldName]);
                }

                // 3. Reordering (Strategi Array Splice - Lebih Stabil)
                // Ambil semua ID dalam jenis ini, urutkan berdasarkan posisi saat ini
                $stmtAll = $pdo->prepare("SELECT id FROM kategori_bagian WHERE jenis_kuesioner = ? ORDER BY position ASC, id ASC");
                $stmtAll->execute([$jenis]);
                $ids = $stmtAll->fetchAll(PDO::FETCH_COLUMN);

                // Hapus ID target dari array (jika ada)
                $key = array_search($id, $ids);
                if ($key !== false) {
                    unset($ids[$key]);
                }
                $ids = array_values($ids); // Reindex array

                // Masukkan ID target ke posisi baru
                // targetPos 1 berarti index 0.
                $insertIndex = $targetPos - 1;
                if ($insertIndex < 0) $insertIndex = 0;
                if ($insertIndex > count($ids)) $insertIndex = count($ids);

                array_splice($ids, $insertIndex, 0, $id);

                // 4. Update semua posisi sesuai urutan array baru
                $stmtUpdatePos = $pdo->prepare("UPDATE kategori_bagian SET position = ? WHERE id = ?");
                foreach ($ids as $index => $currId) {
                    $newP = $index + 1;
                    $stmtUpdatePos->execute([$newP, $currId]);
                }

                $pdo->commit();
            } catch (Exception $e) {
                $pdo->rollBack();
                throw $e;
            }
            $_SESSION['flash_message'] = 'Bagian berhasil diperbarui.';
            header('Location: atur_pertanyaan.php'); exit;
        }

        if (isset($_POST['aksi']) && $_POST['aksi'] === 'delete_bagian') {
            $id = (int)($_POST['bagian_id'] ?? 0);
            if ($id <= 0) throw new Exception('ID bagian tidak valid');
            
            $stmtGet = $pdo->prepare("SELECT jenis_kuesioner, name FROM kategori_bagian WHERE id = ? LIMIT 1");
            $stmtGet->execute([$id]);
            $row = $stmtGet->fetch(PDO::FETCH_ASSOC);
            
            if ($row) {
                // Delete questions first
                $stmtDelQ = $pdo->prepare("DELETE FROM master_pertanyaan WHERE jenis_kuesioner = ? AND kategori_bagian = ?");
                $stmtDelQ->execute([$row['jenis_kuesioner'], $row['name']]);
            }
            
            $stmtDel = $pdo->prepare("DELETE FROM kategori_bagian WHERE id = ?");
            $stmtDel->execute([$id]);
            $_SESSION['flash_message'] = 'Bagian dan seluruh pertanyaan di dalamnya berhasil dihapus.';
            header('Location: atur_pertanyaan.php'); exit;
        }

        // --- Ubah Global Numbering Style untuk Jenis Kuesioner ---
        if (isset($_POST['aksi']) && $_POST['aksi'] === 'set_numbering_style') {
            $jenis = $_POST['jenis'] ?? 'IPLM';
            $style = $_POST['numbering_style'] ?? 'numeric';
            if (!in_array($jenis, ['IPLM', 'TKM'], true)) throw new Exception('Jenis kuesioner tidak valid');
            if (!in_array($style, ['numeric', 'roman', 'none'], true)) throw new Exception('Gaya penomoran tidak valid');
            
            $setting_key = strtolower($jenis) . '_numbering_style';
            $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
            $stmt->execute([$setting_key, $style]);
            $_SESSION['flash_message'] = "Gaya penomoran $jenis berhasil diubah menjadi $style.";
            $redirectTab = $jenis === 'TKM' ? 'tkm' : 'iplm';
            $redirectPageIplm = (int)($_POST['page_iplm'] ?? 1);
            $redirectPageTkm = (int)($_POST['page_tkm'] ?? 1);
            $_SESSION['atur_pertanyaan_state'] = [
                'tab' => $redirectTab,
                'page_iplm' => max(1, $redirectPageIplm),
                'page_tkm' => max(1, $redirectPageTkm),
            ];
            header('Location: atur_pertanyaan.php'); exit;
        }

        if (isset($_POST['aksi']) && ($_POST['aksi'] === 'tambah' || $_POST['aksi'] === 'edit')) {
            $jenisQ = $_POST['jenis'] ?? '';
            $bagianQ = $_POST['bagian'] ?? '';
            
            // Normalize inputs to arrays
            $soalInput = $_POST['soal'] ?? [];
            if (!is_array($soalInput)) $soalInput = [$soalInput];
            
            $keteranganInput = $_POST['keterangan'] ?? [];
            if (!is_array($keteranganInput)) $keteranganInput = [$keteranganInput];
            
            $tipeInput = $_POST['tipe'] ?? [];
            if (!is_array($tipeInput)) $tipeInput = [$tipeInput];
            
            $urutanInput = $_POST['urutan'] ?? [];
            if (!is_array($urutanInput)) $urutanInput = [$urutanInput];
            
            $pilihanInput = $_POST['pilihan_opsi'] ?? [];
            if (!is_array($pilihanInput)) $pilihanInput = [$pilihanInput];

            if (empty($jenisQ) || empty($bagianQ)) {
                throw new Exception("Jenis dan Bagian wajib diisi.");
            }

            if ($_POST['aksi'] === 'tambah') {
                $pdo->beginTransaction();
                try {
                    $count = 0;
                    // Get initial Max Order for the group
                    $stmtMax = $pdo->prepare("SELECT MAX(CAST(urutan AS UNSIGNED)) FROM master_pertanyaan WHERE jenis_kuesioner = ? AND kategori_bagian = ?");
                    $stmtMax->execute([$jenisQ, $bagianQ]);
                    $maxExisting = (int)$stmtMax->fetchColumn();
                    $nextAutoUrutan = $maxExisting + 1;

                    foreach ($soalInput as $index => $soalText) {
                        $soal = trim($soalText);
                        if (empty($soal)) continue; // Skip empty questions

                        $ket = $keteranganInput[$index] ?? '';
                        $tipe = $tipeInput[$index] ?? 'text';
                        $pilihan = $pilihanInput[$index] ?? '';
                        
                        $reqUrutan = (int)($urutanInput[$index] ?? 0);
                        
                        $targetUrutan = ($reqUrutan > 0) ? $reqUrutan : $nextAutoUrutan;
                        
                        $stmtCheckMax = $pdo->prepare("SELECT MAX(CAST(urutan AS UNSIGNED)) FROM master_pertanyaan WHERE jenis_kuesioner = ? AND kategori_bagian = ?");
                        $stmtCheckMax->execute([$jenisQ, $bagianQ]);
                        $currentMax = (int)$stmtCheckMax->fetchColumn();
                        
                        if ($targetUrutan <= $currentMax) {
                            $stmtShift = $pdo->prepare("UPDATE master_pertanyaan SET urutan = urutan + 1 WHERE jenis_kuesioner = ? AND kategori_bagian = ? AND CAST(urutan AS UNSIGNED) >= ?");
                            $stmtShift->execute([$jenisQ, $bagianQ, $targetUrutan]);
                            $maxExisting++; 
                        } else {
                            if ($targetUrutan > $currentMax + 1) $targetUrutan = $currentMax + 1;
                        }

                        $sql = "INSERT INTO master_pertanyaan (jenis_kuesioner, kategori_bagian, teks_pertanyaan, keterangan, tipe_input, pilihan_opsi, urutan) VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$jenisQ, $bagianQ, $soal, $ket, $tipe, $pilihan, $targetUrutan]);
                        
                        $count++;
                        
                        // Increment for next loop if using auto
                        if ($reqUrutan <= 0) $nextAutoUrutan = $targetUrutan + 1;
                    }
                    
                    if ($count === 0) throw new Exception("Tidak ada pertanyaan yang ditambahkan.");
                    
                    $pdo->commit();
                    $pesan = "Berhasil menambah $count pertanyaan baru!";
                } catch (Exception $e) {
                    if ($pdo->inTransaction()) $pdo->rollBack();
                    throw $e;
                }
            } else {
                // EDIT MODE (Single Question)
                $currentId = (int)($_POST['id'] ?? 0);
                
                // Take the first item from arrays
                $soalQ = $soalInput[0] ?? '';
                $keteranganQ = $keteranganInput[0] ?? '';
                $tipeQ = $tipeInput[0] ?? 'text';
                $urutanQ = (int)($urutanInput[0] ?? 0);
                $pilihanQ = $pilihanInput[0] ?? '';
                
                if (empty($soalQ)) throw new Exception("Teks Pertanyaan wajib diisi.");

                $stmtCurrent = $pdo->prepare("SELECT jenis_kuesioner, kategori_bagian, urutan FROM master_pertanyaan WHERE id = ?");
                $stmtCurrent->execute([$currentId]);
                $current = $stmtCurrent->fetch(PDO::FETCH_ASSOC);
                if (!$current) throw new Exception("Data pertanyaan tidak ditemukan.");

                $pdo->beginTransaction();
                try {
                    $sameGroup = ($current['jenis_kuesioner'] === $jenisQ) && ($current['kategori_bagian'] === $bagianQ);
                    $stmtMax = $pdo->prepare("SELECT MAX(CAST(urutan AS UNSIGNED)) FROM master_pertanyaan WHERE jenis_kuesioner = ? AND kategori_bagian = ? AND id <> ?");
                    $stmtMax->execute([$jenisQ, $bagianQ, $currentId]);
                    $maxPos = (int)$stmtMax->fetchColumn() + 1;
                    if ($urutanQ < 1 || $urutanQ > $maxPos) $urutanQ = $maxPos;

                    if ($sameGroup) {
                        $oldUrutan = (int)$current['urutan'];
                        if ($urutanQ < $oldUrutan) {
                            $stmtShift = $pdo->prepare("UPDATE master_pertanyaan SET urutan = urutan + 1 WHERE jenis_kuesioner = ? AND kategori_bagian = ? AND CAST(urutan AS UNSIGNED) >= ? AND CAST(urutan AS UNSIGNED) < ? AND id <> ?");
                            $stmtShift->execute([$jenisQ, $bagianQ, $urutanQ, $oldUrutan, $currentId]);
                        } elseif ($urutanQ > $oldUrutan) {
                            $stmtShift = $pdo->prepare("UPDATE master_pertanyaan SET urutan = urutan - 1 WHERE jenis_kuesioner = ? AND kategori_bagian = ? AND CAST(urutan AS UNSIGNED) > ? AND CAST(urutan AS UNSIGNED) <= ? AND id <> ?");
                            $stmtShift->execute([$jenisQ, $bagianQ, $oldUrutan, $urutanQ, $currentId]);
                        }
                    } else {
                        $stmtClose = $pdo->prepare("UPDATE master_pertanyaan SET urutan = urutan - 1 WHERE jenis_kuesioner = ? AND kategori_bagian = ? AND CAST(urutan AS UNSIGNED) > ?");
                        $stmtClose->execute([$current['jenis_kuesioner'], $current['kategori_bagian'], (int)$current['urutan']]);
                        $stmtShift = $pdo->prepare("UPDATE master_pertanyaan SET urutan = urutan + 1 WHERE jenis_kuesioner = ? AND kategori_bagian = ? AND CAST(urutan AS UNSIGNED) >= ?");
                        $stmtShift->execute([$jenisQ, $bagianQ, $urutanQ]);
                    }

                    $sql = "UPDATE master_pertanyaan SET jenis_kuesioner=?, kategori_bagian=?, teks_pertanyaan=?, keterangan=?, tipe_input=?, pilihan_opsi=?, urutan=? WHERE id=?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$jenisQ, $bagianQ, $soalQ, $keteranganQ, $tipeQ, $pilihanQ, $urutanQ, $currentId]);
                    $pdo->commit();
                    $pesan = "Data pertanyaan berhasil diperbarui!";
                } catch (Exception $e) {
                    if ($pdo->inTransaction()) $pdo->rollBack();
                    throw $e;
                }
            }
        } elseif (isset($_POST['aksi']) && $_POST['aksi'] === 'hapus') {
            $currentId = (int)($_POST['id'] ?? 0);
            $stmtCurrent = $pdo->prepare("SELECT jenis_kuesioner, kategori_bagian, urutan FROM master_pertanyaan WHERE id = ?");
            $stmtCurrent->execute([$currentId]);
            $current = $stmtCurrent->fetch(PDO::FETCH_ASSOC);
            if (!$current) throw new Exception("Data pertanyaan tidak ditemukan.");

            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare("DELETE FROM master_pertanyaan WHERE id = ?");
                $stmt->execute([$currentId]);
                $stmtShift = $pdo->prepare("UPDATE master_pertanyaan SET urutan = urutan - 1 WHERE jenis_kuesioner = ? AND kategori_bagian = ? AND CAST(urutan AS UNSIGNED) > ?");
                $stmtShift->execute([$current['jenis_kuesioner'], $current['kategori_bagian'], (int)$current['urutan']]);
                $pdo->commit();
                $pesan = "Pertanyaan berhasil dihapus.";
            } catch (Exception $e) {
                if ($pdo->inTransaction()) $pdo->rollBack();
                throw $e;
            }
        } else {
            // Jika ada aksi lain yang belum di-handle atau bukan POST yang diinginkan
            if (isset($_POST['aksi'])) return; 
        }

        if ($pesan !== "") {
            $_SESSION['flash_message'] = $pesan;
            $redirectTab = $_POST['tab'] ?? (isset($jenisQ) && $jenisQ === 'TKM' ? 'tkm' : 'iplm');
            $redirectPageIplm = (int)($_POST['page_iplm'] ?? 1);
            $redirectPageTkm = (int)($_POST['page_tkm'] ?? 1);
            $_SESSION['atur_pertanyaan_state'] = [
                'tab' => $redirectTab,
                'page_iplm' => max(1, $redirectPageIplm),
                'page_tkm' => max(1, $redirectPageTkm),
            ];
            header("Location: atur_pertanyaan.php"); exit;
        }

    } catch (Exception $e) { 
        $_SESSION['flash_message_error'] = "Terjadi kesalahan. Silakan coba lagi.";
        header("Location: atur_pertanyaan.php"); exit;
    }
}

if (isset($_SESSION['flash_message_error'])) { 
    $pesan = $_SESSION['flash_message_error'];
    $pesan_type = "danger";
    unset($_SESSION['flash_message_error']); 
} elseif (isset($_SESSION['flash_message'])) { 
    $pesan = $_SESSION['flash_message'];
    $pesan_type = "success";
    unset($_SESSION['flash_message']); 
}

// --- 1b. REFRESH URUTAN OTOMATIS (DIPERBAIKI) ---
// Merapikan urutan: Nomor di-reset per kombinasi Jenis Kuesioner + Kategori/Bagian
// Implementasi PHP untuk konsistensi lintas versi MySQL/MariaDB.
try {
    // Ambil semua grup (jenis_kuesioner + kategori_bagian)
    $stmtGroups = $pdo->query("SELECT DISTINCT jenis_kuesioner, kategori_bagian FROM master_pertanyaan ORDER BY jenis_kuesioner, kategori_bagian");
    $groups = $stmtGroups->fetchAll(PDO::FETCH_ASSOC);
    $pdo->beginTransaction();
    $stmtSelect = $pdo->prepare("SELECT id, urutan FROM master_pertanyaan WHERE jenis_kuesioner = ? AND kategori_bagian = ? ORDER BY CAST(urutan AS UNSIGNED), id");
    $stmtUpdate = $pdo->prepare("UPDATE master_pertanyaan SET urutan = ? WHERE id = ?");
    foreach ($groups as $g) {
        $j = $g['jenis_kuesioner'];
        $k = $g['kategori_bagian'];
        $stmtSelect->execute([$j, $k]);
        $rows = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);
        $n = 1;
        foreach ($rows as $r) {
            $id = $r['id'];
            // hanya update jika berbeda untuk mengurangi query
            if ((int)$r['urutan'] !== $n) {
                $stmtUpdate->execute([$n, $id]);
            }
            $n++;
        }
    }
    $pdo->commit();
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    // jangan gagalkan halaman jika refresh urutan gagal
}

// --- 2. AMBIL DATA (PAGINASI) ---
$per_page = 50;
$state = $_SESSION['atur_pertanyaan_state'] ?? [];
$page_iplm = max(1, (int)($state['page_iplm'] ?? 1));
$page_tkm = max(1, (int)($state['page_tkm'] ?? 1));
$active_tab = $state['tab'] ?? '';

try {
    $stmtCountI = $pdo->query("SELECT COUNT(*) FROM master_pertanyaan WHERE jenis_kuesioner = 'IPLM'");
    $total_iplm = (int)$stmtCountI->fetchColumn();
} catch (Exception $e) { $total_iplm = 0; }

try {
    $stmtCountT = $pdo->query("SELECT COUNT(*) FROM master_pertanyaan WHERE jenis_kuesioner = 'TKM'");
    $total_tkm = (int)$stmtCountT->fetchColumn();
} catch (Exception $e) { $total_tkm = 0; }

$total_pages_iplm = max(1, (int)ceil($total_iplm / $per_page));
$total_pages_tkm = max(1, (int)ceil($total_tkm / $per_page));

$page_iplm = min($page_iplm, $total_pages_iplm);
$page_tkm = min($page_tkm, $total_pages_tkm);

$offset_iplm = ($page_iplm - 1) * $per_page;
$offset_tkm = ($page_tkm - 1) * $per_page;

$sqlIplm = "SELECT m.* FROM master_pertanyaan m "
    . "LEFT JOIN kategori_bagian kb ON kb.jenis_kuesioner = m.jenis_kuesioner AND kb.name = m.kategori_bagian "
    . "WHERE m.jenis_kuesioner = 'IPLM' "
    . "ORDER BY COALESCE(kb.position, 9999) ASC, m.kategori_bagian ASC, CAST(m.urutan AS UNSIGNED) ASC, m.id ASC "
    . "LIMIT " . intval($per_page) . " OFFSET " . intval($offset_iplm);
$stmtIplm = $pdo->prepare($sqlIplm);
$stmtIplm->execute();
$data_iplm = $stmtIplm->fetchAll(PDO::FETCH_ASSOC);

$sqlTkm = "SELECT m.* FROM master_pertanyaan m "
    . "LEFT JOIN kategori_bagian kb ON kb.jenis_kuesioner = m.jenis_kuesioner AND kb.name = m.kategori_bagian "
    . "WHERE m.jenis_kuesioner = 'TKM' "
    . "ORDER BY COALESCE(kb.position, 9999) ASC, m.kategori_bagian ASC, CAST(m.urutan AS UNSIGNED) ASC, m.id ASC "
    . "LIMIT " . intval($per_page) . " OFFSET " . intval($offset_tkm);
$stmtTkm = $pdo->prepare($sqlTkm);
$stmtTkm->execute();
$data_tkm = $stmtTkm->fetchAll(PDO::FETCH_ASSOC);

// --- 2b. MAX URUTAN PER KATEGORI (UNTUK TOMBOL TAMBAH DI BAGIAN) ---
$maxUrutanIplm = [];
try {
    $stmtMaxI = $pdo->prepare("SELECT kategori_bagian, MAX(CAST(urutan AS UNSIGNED)) AS max_urutan FROM master_pertanyaan WHERE jenis_kuesioner = 'IPLM' GROUP BY kategori_bagian");
    $stmtMaxI->execute();
    $rows = $stmtMaxI->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        $key = $r['kategori_bagian'] ?? '';
        if ($key !== '') $maxUrutanIplm[$key] = (int)$r['max_urutan'];
    }
} catch (Exception $e) { $maxUrutanIplm = []; }

$maxUrutanTkm = [];
try {
    $stmtMaxT = $pdo->prepare("SELECT kategori_bagian, MAX(CAST(urutan AS UNSIGNED)) AS max_urutan FROM master_pertanyaan WHERE jenis_kuesioner = 'TKM' GROUP BY kategori_bagian");
    $stmtMaxT->execute();
    $rows = $stmtMaxT->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        $key = $r['kategori_bagian'] ?? '';
        if ($key !== '') $maxUrutanTkm[$key] = (int)$r['max_urutan'];
    }
} catch (Exception $e) { $maxUrutanTkm = []; }

// --- 3. SETTING KONTAK IPLM ---
$kontak_setting_id = '';
try {
    $stmtSettingKontak = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'iplm_kontak_pertanyaan_id' LIMIT 1");
    $stmtSettingKontak->execute();
    $kontak_setting_id = $stmtSettingKontak->fetchColumn();
} catch (Exception $e) { $kontak_setting_id = ''; }

// --- 3.5 GLOBAL NUMBERING STYLE SETTINGS ---
$numbering_style_iplm = 'numeric';
$numbering_style_tkm = 'numeric';
try {
    $stmtSettings = $pdo->prepare("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('iplm_numbering_style', 'tkm_numbering_style')");
    $stmtSettings->execute();
    $styleRows = $stmtSettings->fetchAll(PDO::FETCH_KEY_PAIR);
    $numbering_style_iplm = $styleRows['iplm_numbering_style'] ?? 'numeric';
    $numbering_style_tkm = $styleRows['tkm_numbering_style'] ?? 'numeric';
} catch (Exception $e) {
    // Default values
}

$list_iplm_questions = [];
try {
    $stmtAllIplm = $pdo->prepare("SELECT id, kategori_bagian, teks_pertanyaan FROM master_pertanyaan WHERE jenis_kuesioner = 'IPLM' ORDER BY CAST(urutan AS UNSIGNED) ASC");
    $stmtAllIplm->execute();
    $list_iplm_questions = $stmtAllIplm->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) { $list_iplm_questions = []; }

$autofill_jenis_id = '';
$autofill_subjenis_id = '';
$autofill_nama_id = '';
try {
    $stmtAuto = $pdo->prepare("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('iplm_autofill_jenis_id','iplm_autofill_subjenis_id','iplm_autofill_nama_id')");
    $stmtAuto->execute();
    $autoRows = $stmtAuto->fetchAll(PDO::FETCH_KEY_PAIR);
    $autofill_jenis_id = $autoRows['iplm_autofill_jenis_id'] ?? '';
    $autofill_subjenis_id = $autoRows['iplm_autofill_subjenis_id'] ?? '';
    $autofill_nama_id = $autoRows['iplm_autofill_nama_id'] ?? '';
} catch (Exception $e) {}

// Ambil daftar kategori yang dikelola
$kategori_iplm = [];
$kategori_tkm = [];
try {
    $stmtKat = $pdo->prepare("SELECT id, jenis_kuesioner, name, position, numbering_style FROM kategori_bagian ORDER BY jenis_kuesioner, position ASC");
    $stmtKat->execute();
    $krows = $stmtKat->fetchAll(PDO::FETCH_ASSOC);
    foreach ($krows as $kr) {
        if ($kr['jenis_kuesioner'] === 'TKM') $kategori_tkm[] = $kr;
        else $kategori_iplm[] = $kr;
    }
} catch (Exception $e) {
    $kategori_iplm = []; $kategori_tkm = [];
}

    $this->view('admin/atur_pertanyaan', ['active_tab' => $active_tab ?? 'iplm', 'autofill_jenis_id' => $autofill_jenis_id ?? '', 'autofill_nama_id' => $autofill_nama_id ?? '', 'autofill_subjenis_id' => $autofill_subjenis_id ?? '', 'data_iplm' => $data_iplm ?? [], 'data_tkm' => $data_tkm ?? [], 'kategori_iplm' => $kategori_iplm ?? [], 'kategori_tkm' => $kategori_tkm ?? [], 'kontak_setting_id' => $kontak_setting_id ?? '', 'list_iplm_questions' => $list_iplm_questions ?? [], 'maxUrutanIplm' => $maxUrutanIplm ?? [], 'maxUrutanTkm' => $maxUrutanTkm ?? [], 'numbering_style_iplm' => $numbering_style_iplm ?? 'numeric', 'numbering_style_tkm' => $numbering_style_tkm ?? 'numeric', 'page_iplm' => $page_iplm ?? 1, 'page_tkm' => $page_tkm ?? 1, 'pesan' => $pesan ?? '', 'pesan_type' => $pesan_type ?? 'success', 'total_pages_iplm' => $total_pages_iplm ?? 1, 'total_pages_tkm' => $total_pages_tkm ?? 1]);
}


public function users() {
// web-perpus-v1/admin/users.php
$this->requireAuth();
global $pdo;


// [KEAMANAN] Hanya Super Admin yang boleh akses halaman ini
if (($_SESSION['admin_role'] ?? '') !== 'super') {
    $this->redirect('/admin/dashboard');
    exit;
}

// Set Timezone
date_default_timezone_set('Asia/Makassar');

$pesan = '';
$tipe = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['aksi'])) {
        if ($_POST['aksi'] === 'hapus') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                try {
                    $count = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
                    $targetUser = $pdo->prepare("SELECT is_primary FROM users WHERE id = ?");
                    $targetUser->execute([$id]);
                    $isPrimary = (int)$targetUser->fetchColumn();
                    
                    if ($count <= 1) {
                        $pesan = 'Tidak bisa menghapus akun terakhir.';
                        $tipe = 'danger';
                    } elseif ($isPrimary === 1) {
                        $pesan = 'Akun Admin Utama (Primary) tidak dapat dihapus demi keamanan sistem.';
                        $tipe = 'danger';
                    } elseif ($id === (int)($_SESSION['admin_id'] ?? 0)) {
                        $pesan = 'Anda tidak dapat menghapus akun Anda sendiri.';
                        $tipe = 'danger';
                    } else {
                        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                        $stmt->execute([$id]);
                        $pesan = 'Akun admin berhasil dihapus.';
                    }
                } catch (Exception $e) {
                    $pesan = 'Gagal menghapus akun.';
                    $tipe = 'danger';
                }
            }
        } elseif ($_POST['aksi'] === 'hapus_log') {
            try {
                $pdo->exec("DELETE FROM password_reset_logs");
                if (isset($_POST['ajax'])) {
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'success', 'message' => 'Log berhasil dihapus']);
                    exit;
                }
                $pesan = 'Log reset password berhasil dihapus.';
            } catch (Exception $e) {
                if (isset($_POST['ajax'])) {
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus log.']);
                    exit;
                }
                $pesan = 'Gagal menghapus log.';
                $tipe = 'danger';
            }
        } elseif ($_POST['aksi'] === 'hapus_email_log') {
            try {
                $pdo->exec("DELETE FROM password_reset_email_logs");
                if (isset($_POST['ajax'])) {
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'success', 'message' => 'Log email berhasil dihapus']);
                    exit;
                }
                $pesan = 'Log pengiriman email berhasil dihapus.';
            } catch (Exception $e) {
                if (isset($_POST['ajax'])) {
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus log email.']);
                    exit;
                }
                $pesan = 'Gagal menghapus log email.';
                $tipe = 'danger';
            }
        }
    } else {
        // Add User Logic
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'admin';

        if ($nama === '' || $password === '') {
            $pesan = 'Nama dan password wajib diisi.';
            $tipe = 'danger';
        } else {
            try {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nama, $email !== '' ? $email : null, $hash, $role]);
                $pesan = 'Akun admin berhasil ditambahkan.';
            } catch (Exception $e) {
                if ($e instanceof PDOException) {
                    if ($e->errorInfo[1] == 1062) { // Duplicate entry
                        if (strpos($e->getMessage(), 'email') !== false) {
                            $pesan = 'Email sudah terdaftar. Gunakan email lain.';
                        } else {
                            $pesan = 'Nama sudah digunakan. Pastikan nama unik.';
                        }
                    } elseif ($e->errorInfo[1] == 1048) { // Column cannot be null
                        $pesan = 'Gagal: Email tidak valid atau tidak boleh kosong.';
                    } else {
                        $pesan = 'Gagal menambah akun. Silakan coba lagi.';
                    }
                } else {
                    $pesan = 'Terjadi kesalahan. Silakan coba lagi.';
                }
                $tipe = 'danger';
            }
        }
    }
}

// List users
$users = [];
try {
    $stmt = $pdo->query("SELECT id, nama, email, role, is_primary, created_at FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

$reset_logs = [];
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS password_reset_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        ip_address VARCHAR(64),
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )");
    $stmt = $pdo->query("SELECT l.id, u.nama, l.ip_address, l.created_at
                         FROM password_reset_logs l
                         JOIN users u ON u.id = l.user_id
                         ORDER BY l.created_at DESC
                         LIMIT 20");
    $reset_logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

$email_logs = [];
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS password_reset_email_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(150) NOT NULL,
        status VARCHAR(20) NOT NULL,
        error_message TEXT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )");
    $stmt = $pdo->query("SELECT id, email, status, error_message, created_at
                         FROM password_reset_email_logs
                         ORDER BY created_at DESC
                         LIMIT 20");
    $email_logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

    $this->view('admin/users', ['email_logs' => $email_logs ?? [], 'pesan' => $pesan ?? '', 'reset_logs' => $reset_logs ?? [], 'tipe' => $tipe ?? 'success', 'users' => $users ?? []]);
}

}
