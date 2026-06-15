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
}
