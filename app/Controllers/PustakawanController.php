<?php
namespace Controllers;

use Core\Controller;

class PustakawanController extends Controller {

    public function index() {
        $this->redirect('/');
    }

    public function kuisioner_tkm() {
        if (!session_id()) session_start();
        
        $settingModel = $this->model('SettingModel');
        $questionModel = $this->model('QuestionModel');

        $settings = $settingModel->getAllSettings();

        // --- CEK STATUS & JADWAL ---
        date_default_timezone_set('Asia/Makassar');
        $mode = $settings['tkm_mode'] ?? 'manual';
        $isOpen = false;
        $pesanTutup = "Periode Pengisian Kuisioner Belum Dibuka";

        if ($mode == 'manual') {
            if (($settings['status_tkm'] ?? 'buka') == 'buka') {
                $isOpen = true;
            }
        } else {
            $now = time();
            $start = str_replace('T', ' ', $settings['tkm_start'] ?? '');
            $end = str_replace('T', ' ', $settings['tkm_end'] ?? '');

            if ($start && $end) {
                $startTs = strtotime($start);
                $endTs = strtotime($end);
                if ($startTs && $endTs && $now >= $startTs && $now <= $endTs) {
                    $isOpen = true;
                } elseif ($startTs && $now < $startTs) {
                    $pesanTutup = "Kuesioner belum dibuka.<br>Jadwal Buka: <strong>" . date('d M Y H:i', $startTs) . "</strong>";
                } else {
                    $pesanTutup = "Kuesioner sudah ditutup.<br>Batas Akhir: <strong>" . date('d M Y H:i', $endTs ?: strtotime($end)) . "</strong>";
                }
            } else {
                $pesanTutup = "Jadwal pengisian belum diatur oleh admin.";
            }
        }

        if (!$isOpen) {
            $this->view('pustakawan/tutup', ['pesanTutup' => $pesanTutup]);
            return;
        }

        if (!empty($_POST) || !empty($_GET)) {
            $incoming = $_POST['library_id'] ?? ($_GET['library_id'] ?? null);
            if ($incoming !== null && $incoming !== '') {
                $_SESSION['pustakawan_ctx']['library_id'] = $incoming;
            }
            if (!empty($_GET['library_id']) || !empty($_GET['target'])) {
                $this->redirect('/pustakawan/kuisioner_tkm');
                return;
            }
        }

        $library_id = $_SESSION['pustakawan_ctx']['library_id'] ?? '';
        $auto_isi = []; 

        $sections = $questionModel->getSections('TKM');
        $total_questions = $questionModel->getTotalQuestions('TKM');

        $data = [
            'library_id' => $library_id,
            'auto_isi' => $auto_isi,
            'sections' => $sections,
            'total_questions' => $total_questions
        ];

        $this->view('pustakawan/kuisioner_tkm', $data);
    }

    public function kuisioner_iplm() {
        if (!session_id()) session_start();
        
        $settingModel = $this->model('SettingModel');
        $questionModel = $this->model('QuestionModel');

        $settings = $settingModel->getAllSettings();

        // --- CEK STATUS & JADWAL ---
        date_default_timezone_set('Asia/Makassar');
        $mode = $settings['iplm_mode'] ?? 'manual';
        $isOpen = false;
        $pesanTutup = "Periode Pengisian Kuisioner Belum Dibuka";

        if ($mode == 'manual') {
            if (($settings['status_iplm'] ?? 'buka') == 'buka') {
                $isOpen = true;
            }
        } else {
            $now = time();
            $start = str_replace('T', ' ', $settings['iplm_start'] ?? '');
            $end = str_replace('T', ' ', $settings['iplm_end'] ?? '');

            if ($start && $end) {
                $startTs = strtotime($start);
                $endTs = strtotime($end);
                if ($startTs && $endTs && $now >= $startTs && $now <= $endTs) {
                    $isOpen = true;
                } elseif ($startTs && $now < $startTs) {
                    $pesanTutup = "Kuesioner belum dibuka.<br>Jadwal Buka: <strong>" . date('d M Y H:i', $startTs) . "</strong>";
                } else {
                    $pesanTutup = "Kuesioner sudah ditutup.<br>Batas Akhir: <strong>" . date('d M Y H:i', $endTs ?: strtotime($end)) . "</strong>";
                }
            } else {
                $pesanTutup = "Jadwal pengisian belum diatur oleh admin.";
            }
        }

        if (!$isOpen) {
            $this->view('pustakawan/tutup', ['pesanTutup' => $pesanTutup]);
            return;
        }

        // --- PROSES VERIFIKASI TOKEN (IPLM) ---
        $tokenInput = $_GET['token'] ?? ($_POST['token'] ?? null);
        if (!empty($tokenInput)) {
            $libraryModel = $this->model('LibraryModel');
            $lib = $libraryModel->getLibraryByToken(trim($tokenInput));
            if (!$lib) {
                $this->view('pustakawan/token_invalid');
                return;
            }
            $_SESSION['pustakawan_ctx'] = [
                'library_id' => $lib['id'],
                'kategori_utama' => $lib['kategori'] ?? 'Umum',
                'kategori_sub' => $lib['jenis'] ?? '',
                'nama_perpus_text' => $lib['nama'] ?? '',
                'target' => 'iplm'
            ];
            $this->redirect('/pustakawan/kuisioner_iplm');
            return;
        }

        $library_id = $_SESSION['pustakawan_ctx']['library_id'] ?? '';
        $kat_utama  = $_SESSION['pustakawan_ctx']['kategori_utama'] ?? '';
        $kat_sub    = $_SESSION['pustakawan_ctx']['kategori_sub'] ?? '';
        $nama_text  = $_SESSION['pustakawan_ctx']['nama_perpus_text'] ?? '';
        $target_ctx = $_SESSION['pustakawan_ctx']['target'] ?? '';

        if (!$library_id || $target_ctx !== 'iplm') {
            $this->redirect('/pustakawan/pilih_perpustakaan?target=iplm');
            return;
        }

        $auto_isi = [
            'core_jenis'    => "Perpustakaan " . $kat_utama,
            'core_subjenis' => $kat_sub,
            'core_nama'     => $nama_text
        ];

        $sections = $questionModel->getSections('IPLM');
        $total_questions = $questionModel->getTotalQuestions('IPLM');

        $data = [
            'library_id' => $library_id,
            'auto_isi' => $auto_isi,
            'sections' => $sections,
            'total_questions' => $total_questions
        ];

        $this->view('pustakawan/kuisioner_iplm', $data);
    }

    public function form_pengaduan() {
        $this->view('pustakawan/form_pengaduan');
    }

    public function proses_pengaduan() {
        if (!session_id()) session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once BASE_PATH . 'config/public_security.php';
            verify_public_csrf();

            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $rl = rate_limit_check('pengaduan:' . $ip, 5, 3600);
            if (!$rl['allowed']) {
                $msg = 'Anda terlalu sering mengirim pengaduan. Coba lagi dalam ' . ceil($rl['retry_after'] / 60) . ' menit.';
                $this->view('pustakawan/proses_alert', ['icon' => 'warning', 'title' => 'Terlalu Banyak Permintaan', 'text' => $msg, 'redirect' => BASE_URL . '/pustakawan/form_pengaduan']);
                return;
            }

            $nama = !empty($_POST['nama']) ? $_POST['nama'] : 'Anonim';
            $kontak = !empty($_POST['kontak']) ? $_POST['kontak'] : '-';
            $pesan_raw = $_POST['pesan'] ?? '';

            $badwords = require BASE_PATH . 'config/profanity.php';
            $filtered = false;

            $filterBadWords = function ($text, $badwords, &$flag) {
                $clean = $text;
                foreach ($badwords as $word) {
                    $w = trim($word);
                    if ($w === '') continue;
                    $pattern = '/\b' . preg_quote($w, '/') . '\b/i';
                    if (preg_match($pattern, $clean)) {
                        $flag = true;
                        $clean = preg_replace($pattern, '***', $clean);
                    }
                }
                return $clean;
            };

            $pesan = $filterBadWords($pesan_raw, $badwords, $filtered);

            try {
                $complaintModel = $this->model('ComplaintModel');
                $complaintModel->insertComplaint($nama, $kontak, $pesan);

                $popupText = $filtered
                    ? 'Terima kasih! Laporan/Saran Anda telah kami terima. Beberapa kata tidak pantas telah disensor.'
                    : 'Terima kasih! Laporan/Saran Anda telah kami terima.';
                
                $this->view('pustakawan/proses_alert', ['icon' => 'success', 'title' => 'Terima kasih!', 'text' => $popupText, 'redirect' => BASE_URL]);
            } catch (\Exception $e) {
                error_log("Pengaduan Error: " . $e->getMessage());
                die("Terjadi kesalahan. Silakan coba lagi nanti.");
            }
        }
    }

    public function pilih_perpustakaan() {
        if (!session_id()) session_start();
        
        $ctx = $_SESSION['pustakawan_ctx'] ?? [];
        if (isset($_GET['target'])) {
            $ctx['target'] = $_GET['target'];
            $_SESSION['pustakawan_ctx'] = $ctx;
            $this->redirect('/pustakawan/pilih_perpustakaan');
            return;
        }
        $target = $ctx['target'] ?? 'index';

        $libraryModel = $this->model('LibraryModel');
        $libraries = $libraryModel->getAllLibraries();
        $rawKategori = $libraryModel->getMasterCategories();

        $strukturJenis = [];
        foreach ($rawKategori as $row) {
            $strukturJenis[$row['kategori']][] = $row['sub_kategori'];
        }

        $data = [
            'target' => $target,
            'libraries' => $libraries,
            'strukturJenis' => $strukturJenis
        ];

        $this->view('pustakawan/pilih_perpustakaan', $data);
    }

    public function proses_simpan() {
        if (!session_id()) session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            date_default_timezone_set('Asia/Makassar');
            
            require_once BASE_PATH . 'config/public_security.php';
            verify_public_csrf();

            $library_id = !empty($_POST['library_id']) ? $_POST['library_id'] : null;
            $jenis = $_POST['jenis_kuesioner'] ?? '';
            if ($jenis === 'IPLM') {
                $sess_lib = $_SESSION['pustakawan_ctx']['library_id'] ?? null;
                $sess_target = $_SESSION['pustakawan_ctx']['target'] ?? null;
                if (!$sess_lib || $sess_target !== 'iplm' || (string)$sess_lib !== (string)$library_id) {
                    die("Akses tidak sah. Silakan gunakan link atau kode token resmi untuk mengisi survei IPLM.");
                }
            }
            $jawaban = $_POST['jawaban'] ?? []; // Array [id_soal => isi_jawaban]
            $periode_bulan = date('m');
            $periode_tahun = date('Y');

            $normalize_kontak = function ($value) {
                $v = trim((string)$value);
                $v = strtolower($v);
                if ($v === '') return '';
                if (strpos($v, '@') !== false) return $v;
                $digits = preg_replace('/\D+/', '', $v);
                return $digits !== '' ? $digits : $v;
            };

            $renderErrorPopup = function ($title, $message) {
                echo "<!DOCTYPE html><html lang='id'><head>
                        <meta charset='UTF-8'>
                        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                        <title>Peringatan</title>
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                      </head><body>
                        <script>
                            Swal.fire({
                                icon: 'warning',
                                title: " . json_encode($title) . ",
                                text: " . json_encode($message) . ",
                                confirmButtonColor: '#111'
                            }).then(() => {
                                window.location = '" . BASE_URL . "';
                            });
                        </script>
                      </body></html>";
                exit;
            };

            try {
                $db = new \Core\Database();

                // --- RATE LIMIT (KUISIONER) ---
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $key = 'kuesioner:' . $ip;
                $rl = rate_limit_check($key, 20, 3600);
                if (!$rl['allowed']) {
                    $renderErrorPopup(
                        'Terlalu Banyak Permintaan',
                        'Anda terlalu sering mengirim kuisioner. Coba lagi dalam ' . ceil($rl['retry_after'] / 60) . ' menit.'
                    );
                }

                // --- VALIDASI DUPLIKASI (KHUSUS IPLM) ---
                if ($jenis === 'IPLM') {
                    // 1) Perpustakaan yang sama tidak bisa mengisi 2x dalam bulan yang sama
                    if (!empty($library_id)) {
                        $db->query("SELECT 1 FROM trans_header WHERE jenis_kuesioner = 'IPLM' AND library_id = :library_id AND periode_bulan = :periode_bulan AND periode_tahun = :periode_tahun LIMIT 1");
                        $db->bind(':library_id', $library_id);
                        $db->bind(':periode_bulan', $periode_bulan);
                        $db->bind(':periode_tahun', $periode_tahun);
                        if ($db->fetchColumn()) {
                            $renderErrorPopup(
                                'Pengisian Ditolak',
                                "Perpustakaan ini sudah mengisi IPLM pada periode {$periode_bulan}/{$periode_tahun}."
                            );
                        }
                    }

                    // 2) Orang yang sama (kontak sama) tidak boleh mengisi perpus berbeda pada bulan yang sama
                    $db->query("SELECT setting_value FROM settings WHERE setting_key = 'iplm_kontak_pertanyaan_id' LIMIT 1");
                    $kontak_setting_id = $db->fetchColumn();

                    $kontak_ids = [];
                    if (!empty($kontak_setting_id)) {
                        $kontak_ids = [(int)$kontak_setting_id];
                    } else {
                        $db->query("
                            SELECT id FROM master_pertanyaan 
                            WHERE jenis_kuesioner = 'IPLM' 
                            AND (
                                LOWER(teks_pertanyaan) LIKE :q1 OR
                                LOWER(teks_pertanyaan) LIKE :q2 OR
                                LOWER(teks_pertanyaan) LIKE :q3 OR
                                LOWER(teks_pertanyaan) LIKE :q4 OR
                                LOWER(teks_pertanyaan) LIKE :q5 OR
                                LOWER(teks_pertanyaan) LIKE :q6 OR
                                LOWER(teks_pertanyaan) LIKE :q7 OR
                                LOWER(teks_pertanyaan) LIKE :q8
                            )
                        ");
                        $db->bind(':q1', '%kontak pengisi kuesioner%');
                        $db->bind(':q2', '%whatsapp aktif%');
                        $db->bind(':q3', '%kontak%');
                        $db->bind(':q4', '%no hp%');
                        $db->bind(':q5', '%no. hp%');
                        $db->bind(':q6', '%telepon%');
                        $db->bind(':q7', '%whatsapp%');
                        $db->bind(':q8', '%email%');
                        
                        $results = $db->resultSet();
                        $kontak_ids = array_column($results, 'id');

                        if (count($kontak_ids) === 1) {
                            $db->query("UPDATE settings SET setting_value = :val WHERE setting_key = 'iplm_kontak_pertanyaan_id'");
                            $db->bind(':val', $kontak_ids[0]);
                            $db->execute();
                        }
                    }

                    $kontak_input = '';
                    foreach ($kontak_ids as $kid) {
                        if (isset($jawaban[$kid]) && trim($jawaban[$kid]) !== '') {
                            $kontak_input = $jawaban[$kid];
                            break;
                        }
                    }
                    $kontak_norm = $normalize_kontak($kontak_input);

                    if (!empty($kontak_ids) && $kontak_norm !== '') {
                        $is_digits = ctype_digit($kontak_norm);
                        $paramNames = [];
                        for ($i = 0; $i < count($kontak_ids); $i++) {
                            $paramNames[] = ':kid_' . $i;
                        }
                        $placeholders = implode(',', $paramNames);

                        if ($is_digits) {
                            $sqlCekKontak = "
                                SELECT 1
                                FROM trans_header h
                                JOIN trans_detail d ON d.header_id = h.id
                                WHERE h.jenis_kuesioner = 'IPLM'
                                  AND h.periode_bulan = :periode_bulan
                                  AND h.periode_tahun = :periode_tahun
                                  AND NOT (h.library_id <=> :library_id)
                                  AND d.pertanyaan_id IN ($placeholders)
                                  AND REGEXP_REPLACE(d.jawaban, '[^0-9]', '') = :kontak_norm
                                LIMIT 1
                            ";
                        } else {
                            $sqlCekKontak = "
                                SELECT 1
                                FROM trans_header h
                                JOIN trans_detail d ON d.header_id = h.id
                                WHERE h.jenis_kuesioner = 'IPLM'
                                  AND h.periode_bulan = :periode_bulan
                                  AND h.periode_tahun = :periode_tahun
                                  AND NOT (h.library_id <=> :library_id)
                                  AND d.pertanyaan_id IN ($placeholders)
                                  AND LOWER(TRIM(d.jawaban)) = :kontak_norm
                                LIMIT 1
                            ";
                        }

                        $db->query($sqlCekKontak);
                        $db->bind(':periode_bulan', $periode_bulan);
                        $db->bind(':periode_tahun', $periode_tahun);
                        $db->bind(':library_id', $library_id);
                        $db->bind(':kontak_norm', $kontak_norm);
                        for ($i = 0; $i < count($kontak_ids); $i++) {
                            $db->bind(':kid_' . $i, $kontak_ids[$i]);
                        }

                        if ($db->fetchColumn()) {
                            $renderErrorPopup(
                                'Pengisian Ditolak',
                                "Kontak ini sudah pernah digunakan untuk mengisi IPLM di perpustakaan lain pada periode {$periode_bulan}/{$periode_tahun}."
                            );
                        }
                    }
                }

                $db->beginTransaction();

                // 1. Simpan Header
                $db->query("INSERT INTO trans_header (library_id, jenis_kuesioner, periode_bulan, periode_tahun) VALUES (:library_id, :jenis, :periode_bulan, :periode_tahun)");
                $db->bind(':library_id', $library_id);
                $db->bind(':jenis', $jenis);
                $db->bind(':periode_bulan', $periode_bulan);
                $db->bind(':periode_tahun', $periode_tahun);
                $db->execute();
                
                $header_id = $db->lastInsertId();

                // 2. Simpan Detail
                $db->query("INSERT INTO trans_detail (header_id, pertanyaan_id, jawaban) VALUES (:header_id, :pertanyaan_id, :jawaban)");

                foreach ($jawaban as $soal_id => $isi) {
                    $db->bind(':header_id', $header_id);
                    $db->bind(':pertanyaan_id', $soal_id);
                    $db->bind(':jawaban', $isi);
                    $db->execute();
                }

                $db->commit();
                
                // Redirect kembali dengan pesan sukses
                echo "<!DOCTYPE html><html lang='id'><head>
                        <meta charset='UTF-8'>
                        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                        <title>Berhasil</title>
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                      </head><body>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Terima kasih!',
                                text: " . json_encode("Data $jenis berhasil disimpan.") . ",
                                confirmButtonColor: '#111'
                            }).then(() => {
                                window.location='" . BASE_URL . "';
                            });
                        </script>
                      </body></html>";

            } catch (\Exception $e) {
                if (isset($db)) $db->rollBack();
                error_log("Error System: " . $e->getMessage());
                die("Terjadi kesalahan sistem. Silakan coba beberapa saat lagi atau hubungi administrator.");
            }
        }
    }
}
