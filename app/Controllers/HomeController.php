<?php
namespace Controllers;

use Core\Controller;

class HomeController extends Controller {
    public function index() {
        $libraryModel = $this->model('LibraryModel');
        $total_libraries = $libraryModel->getTotalLibraries();

        date_default_timezone_set('Asia/Makassar');
        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $bulanIndo = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        $tgl_sekarang = $hari[date('w')] . ', ' . date('d') . ' ' . $bulanIndo[date('m')] . ' ' . date('Y');

        $this->view('home/index', [
            'total_libraries' => $total_libraries,
            'tgl_sekarang' => $tgl_sekarang
        ]);
    }
}
