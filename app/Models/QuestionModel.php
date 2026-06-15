<?php
namespace Models;

use Core\Database;

class QuestionModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getSections($jenis_kuesioner) {
        $this->db->query("
            SELECT m.kategori_bagian, COUNT(*) as total_questions
            FROM master_pertanyaan m
            LEFT JOIN kategori_bagian kb ON kb.jenis_kuesioner = m.jenis_kuesioner AND kb.name = m.kategori_bagian
            WHERE m.jenis_kuesioner = :jenis_kuesioner
            GROUP BY m.kategori_bagian, kb.position
            ORDER BY COALESCE(kb.position, 9999) ASC, m.kategori_bagian ASC
        ");
        $this->db->bind(':jenis_kuesioner', $jenis_kuesioner);
        return $this->db->resultSet();
    }

    public function getTotalQuestions($jenis_kuesioner) {
        $this->db->query("SELECT COUNT(*) FROM master_pertanyaan WHERE jenis_kuesioner = :jenis_kuesioner");
        $this->db->bind(':jenis_kuesioner', $jenis_kuesioner);
        return $this->db->fetchColumn();
    }
}
