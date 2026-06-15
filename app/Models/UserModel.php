<?php
namespace Models;

use Core\Database;

class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getUserByUsername($username) {
        $this->db->query("SELECT id, nama, password, role FROM users WHERE nama = :username LIMIT 1");
        $this->db->bind(':username', $username);
        return $this->db->single();
    }
}
