<?php
namespace Models;

use Core\Database;

class ComplaintModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function insertComplaint($nama, $kontak, $pesan) {
        $this->db->query("INSERT INTO pengaduan (nama, kontak, pesan, tanggal) VALUES (?, ?, ?, CURRENT_DATE)");
        $this->db->bind(1, $nama);
        $this->db->bind(2, $kontak);
        $this->db->bind(3, $pesan);
        return $this->db->execute();
    }

    public function ensureColumnsExist() {
        try {
            // Note: $this->db->dbh is private, so we might need a workaround or add a direct exec method to Database, or just query it.
            $this->db->query("ALTER TABLE pengaduan ADD COLUMN IF NOT EXISTS is_important TINYINT(1) NOT NULL DEFAULT 0");
            $this->db->execute();
            $this->db->query("ALTER TABLE pengaduan ADD COLUMN IF NOT EXISTS is_done TINYINT(1) NOT NULL DEFAULT 0");
            $this->db->execute();
        } catch (\Exception $e) {}
    }

    public function deleteComplaint($id) {
        $this->db->query("DELETE FROM pengaduan WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function toggleImportant($id) {
        $this->db->query("UPDATE pengaduan SET is_important = NOT COALESCE(is_important, FALSE) WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function toggleDone($id) {
        $this->db->query("UPDATE pengaduan SET is_done = NOT COALESCE(is_done, FALSE) WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getGrafikData($tahun) {
        $this->db->query("SELECT 
                EXTRACT(MONTH FROM created_at) AS bln, 
                COUNT(*) AS total,
                SUM(CASE WHEN COALESCE(is_important, FALSE) = TRUE THEN 1 ELSE 0 END) AS important_count,
                SUM(CASE WHEN COALESCE(is_done, FALSE) = TRUE THEN 1 ELSE 0 END) AS done_count
            FROM pengaduan
            WHERE EXTRACT(YEAR FROM created_at) = :thn
            GROUP BY EXTRACT(MONTH FROM created_at)
            ORDER BY bln ASC");
        $this->db->bind(':thn', $tahun);
        return $this->db->resultSet();
    }

    public function getFilteredComplaints($bulan, $tahun, $penting) {
        $where = "EXTRACT(MONTH FROM created_at) = :bln AND EXTRACT(YEAR FROM created_at) = :thn";
        if ($penting === '1') {
            $where .= " AND COALESCE(is_important, FALSE) = TRUE";
        } elseif ($penting === '0') {
            $where .= " AND COALESCE(is_important, FALSE) = FALSE";
        }
        $this->db->query("SELECT * FROM pengaduan WHERE $where ORDER BY created_at DESC");
        $this->db->bind(':bln', $bulan);
        $this->db->bind(':thn', $tahun);
        return $this->db->resultSet();
    }
}
