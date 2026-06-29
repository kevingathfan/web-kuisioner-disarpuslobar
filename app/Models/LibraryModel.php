<?php
namespace Models;

use Core\Database;

class LibraryModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getTotalLibraries() {
        $this->db->query("SELECT COUNT(*) FROM libraries");
        return $this->db->fetchColumn();
    }

    public function getAllLibraries() {
        $this->db->query("SELECT id, nama, jenis FROM libraries ORDER BY nama ASC");
        return $this->db->resultSet();
    }

    public function getMasterCategories() {
        $this->db->query("SELECT kategori, sub_kategori FROM master_kategori ORDER BY kategori ASC, sub_kategori ASC");
        return $this->db->resultSet();
    }

    /**
     * Cari perpustakaan berdasarkan token akses.
     * @return array|null Data perpustakaan atau null jika tidak ditemukan
     */
    public function getLibraryByToken($token) {
        $this->db->query("SELECT id, nama, jenis, lokasi, kategori, token FROM libraries WHERE token = :token LIMIT 1");
        $this->db->bind(':token', $token);
        $result = $this->db->single();
        return $result ?: null;
    }

    /**
     * Generate token acak unik untuk semua perpustakaan yang belum memiliki token.
     * @return int Jumlah token yang di-generate
     */
    public function generateAllTokens() {
        $this->db->query("SELECT id FROM libraries WHERE token IS NULL OR token = ''");
        $rows = $this->db->resultSet();

        $count = 0;
        foreach ($rows as $row) {
            $token = bin2hex(random_bytes(16)); // 32 karakter hex
            $this->db->query("UPDATE libraries SET token = :token WHERE id = :id");
            $this->db->bind(':token', $token);
            $this->db->bind(':id', $row['id']);
            $this->db->execute();
            $count++;
        }
        return $count;
    }
}
