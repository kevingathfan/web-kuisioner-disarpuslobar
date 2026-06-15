<?php
namespace Models;

use Core\Database;

class SettingModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllSettings() {
        $this->db->query("SELECT * FROM settings");
        return $this->db->fetchKeyPair();
    }

    public function updateSetting($key, $value) {
        $this->db->query("UPDATE settings SET setting_value = :val WHERE setting_key = :key");
        $this->db->bind(':val', $value);
        $this->db->bind(':key', $key);
        return $this->db->execute();
    }
}
