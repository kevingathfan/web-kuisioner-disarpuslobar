<?php
namespace Models;

use Core\Database;

class DashboardModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getMonthlyStats($jenis, $tahun) {
        $this->db->query("SELECT periode_bulan as bulan, COUNT(*) as total 
            FROM trans_header 
            WHERE jenis_kuesioner = :jenis 
            AND periode_tahun = :thn 
            GROUP BY periode_bulan 
            ORDER BY periode_bulan ASC");
        
        $this->db->bind(':jenis', $jenis);
        $this->db->bind(':thn', $tahun);
        $hasil = $this->db->fetchKeyPair();

        $data_final = [];
        for ($i = 1; $i <= 12; $i++) {
            $key = str_pad($i, 2, '0', STR_PAD_LEFT); 
            $data_final[] = isset($hasil[$key]) ? (int)$hasil[$key] : 0;
        }
        return $data_final;
    }

    public function getRangeStats($jenis, $start_key, $end_key) {
        $this->db->query("SELECT COUNT(*) FROM trans_header 
            WHERE jenis_kuesioner = :jenis 
            AND CONCAT(LPAD(periode_tahun, 4, '0'), '-', LPAD(periode_bulan, 2, '0')) BETWEEN :start_key AND :end_key");
        $this->db->bind(':jenis', $jenis);
        $this->db->bind(':start_key', $start_key);
        $this->db->bind(':end_key', $end_key);
        return $this->db->fetchColumn();
    }

    public function getTkmDemographics($type, $start_key, $end_key) {
        if ($type === 'usia') {
            $this->db->query("SELECT id, pilihan_opsi FROM master_pertanyaan WHERE jenis_kuesioner = 'TKM' AND (LOWER(teks_pertanyaan) LIKE :query1 OR LOWER(teks_pertanyaan) LIKE :query2) LIMIT 1");
            $this->db->bind(':query1', '%usia%');
            $this->db->bind(':query2', '%umur%');
        } else if ($type === 'gender') {
            $this->db->query("SELECT id, pilihan_opsi FROM master_pertanyaan WHERE jenis_kuesioner = 'TKM' AND (LOWER(teks_pertanyaan) LIKE :query1 OR LOWER(teks_pertanyaan) LIKE :query2) LIMIT 1");
            $this->db->bind(':query1', '%jenis kelamin%');
            $this->db->bind(':query2', '%gender%');
        } else {
            return [];
        }
        
        $qRow = $this->db->single();
        if (!$qRow) {
            return [];
        }
        $pertanyaan_id = $qRow['id'];
        $pilihan_opsi = $qRow['pilihan_opsi'] ?? '';
        
        $data_map = [];
        if (!empty($pilihan_opsi)) {
            $options = explode(',', $pilihan_opsi);
            foreach ($options as $opt) {
                $trimmed = trim($opt);
                if ($trimmed !== '') {
                    $data_map[$trimmed] = 0;
                }
            }
        }
        
        $this->db->query("
            SELECT TRIM(td.jawaban) as label, COUNT(*) as total
            FROM trans_detail td
            JOIN trans_header th ON td.header_id = th.id
            WHERE th.jenis_kuesioner = 'TKM'
              AND td.pertanyaan_id = :pertanyaan_id
              AND CONCAT(LPAD(th.periode_tahun, 4, '0'), '-', LPAD(th.periode_bulan, 2, '0')) BETWEEN :start_key AND :end_key
            GROUP BY TRIM(td.jawaban)
        ");
        $this->db->bind(':pertanyaan_id', $pertanyaan_id);
        $this->db->bind(':start_key', $start_key);
        $this->db->bind(':end_key', $end_key);
        
        $results = $this->db->resultSet();
        foreach ($results as $row) {
            $lbl = trim($row['label']);
            if ($lbl !== '') {
                $data_map[$lbl] = (int)$row['total'];
            }
        }
        
        return $data_map;
    }
}
