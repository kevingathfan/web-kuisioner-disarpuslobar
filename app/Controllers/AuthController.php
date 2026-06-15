<?php
namespace Controllers;

use Core\Controller;

class AuthController extends Controller {

    public function index() {
        $this->redirect('/auth/login');
    }

    public function login() {
        if (!session_id()) session_start();
        
        if (!empty($_SESSION['admin_logged_in'])) {
            $this->redirect('/admin/dashboard');
            return;
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                $error = 'Invalid security token. Please try again.';
            } else {
                $max_attempts = 5;
                $lock_minutes = 5;
                $now = time();

                if (!isset($_SESSION['login_attempts'])) $_SESSION['login_attempts'] = 0;
                if (!isset($_SESSION['login_lock_until'])) $_SESSION['login_lock_until'] = 0;

                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $ip_key = hash('sha256', $ip);
                $rate_file = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'login_rate_' . $ip_key . '.json';
                $rate_data = ['attempts' => 0, 'lock_until' => 0];
                if (is_file($rate_file)) {
                    $raw = @file_get_contents($rate_file);
                    $decoded = json_decode($raw, true);
                    if (is_array($decoded)) $rate_data = array_merge($rate_data, $decoded);
                }

                $session_locked = $now < (int)$_SESSION['login_lock_until'];
                $ip_locked = $now < (int)$rate_data['lock_until'];

                if ($session_locked || $ip_locked) {
                    $lock_until = max((int)$_SESSION['login_lock_until'], (int)$rate_data['lock_until']);
                    $sisa = $lock_until - $now;
                    $error = "Terlalu banyak percobaan. Coba lagi dalam " . ceil($sisa / 60) . " menit.";
                } else {
                    $username = $_POST['username'] ?? '';
                    $password = $_POST['password'] ?? '';

                    $userModel = $this->model('UserModel');
                    $user = $userModel->getUserByUsername($username);

                    if ($user && password_verify($password, $user['password'])) {
                        session_regenerate_id(true);
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_id'] = $user['id'];
                        $_SESSION['admin_name'] = $user['nama'];
                        $_SESSION['admin_role'] = $user['role'];
                        $_SESSION['login_attempts'] = 0;
                        $_SESSION['login_lock_until'] = 0;
                        $rate_data['attempts'] = 0;
                        $rate_data['lock_until'] = 0;
                        @file_put_contents($rate_file, json_encode($rate_data));
                        
                        $this->redirect('/admin/dashboard');
                        return;
                    } else {
                        $_SESSION['login_attempts']++;
                        $rate_data['attempts']++;
                        if ($_SESSION['login_attempts'] >= $max_attempts) {
                            $_SESSION['login_lock_until'] = $now + ($lock_minutes * 60);
                            $error = "Terlalu banyak percobaan. Coba lagi dalam $lock_minutes menit.";
                        } else {
                            $sisa = $max_attempts - $_SESSION['login_attempts'];
                            $error = "Username atau Password salah! Sisa percobaan: $sisa";
                        }
                        if ($rate_data['attempts'] >= $max_attempts) {
                            $rate_data['lock_until'] = $now + ($lock_minutes * 60);
                        }
                        @file_put_contents($rate_file, json_encode($rate_data));
                    }
                }
            }
        }

        $this->view('admin/login', ['error' => $error]);
    }

    public function logout() {
        if (!session_id()) session_start();
        session_destroy();
        $this->redirect('/auth/login');
    }
}
