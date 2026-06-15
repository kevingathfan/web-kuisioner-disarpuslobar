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

    public function forgot_password() {
        if (!session_id()) session_start();

        if (!empty($_SESSION['admin_logged_in'])) {
            $this->redirect('/admin/dashboard');
            return;
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $pesan = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                $pesan = 'Invalid security token. Please try again.';
            } else {
                $email = trim($_POST['email'] ?? '');
                
                // Rate limit
                require_once BASE_PATH . 'config/public_security.php';
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $rl = rate_limit_check('reset:' . $ip, 5, 3600);
                if (!$rl['allowed']) {
                    $pesan = 'Terlalu banyak permintaan. Coba lagi nanti.';
                } else {
                    $db = new \Core\Database();
                    $db->query("SELECT id, nama, email FROM users WHERE email = :email LIMIT 1");
                    $db->bind(':email', $email);
                    $user = $db->single();

                    if ($user) {
                        // Create tables password_resets and password_reset_email_logs if they don't exist
                        try {
                            $db->query("CREATE TABLE IF NOT EXISTS password_reset_email_logs (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                email VARCHAR(150) NOT NULL,
                                status VARCHAR(20) NOT NULL,
                                error_message TEXT NULL,
                                token_hash VARCHAR(255) NULL,
                                expires_at TIMESTAMP NULL,
                                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                            )");
                            $db->execute();
                        } catch (\Exception $e) {}

                        try {
                            $db->query("CREATE TABLE IF NOT EXISTS password_resets (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                user_id INT NOT NULL,
                                token_hash VARCHAR(255) NOT NULL,
                                expires_at TIMESTAMP NOT NULL,
                                used_at TIMESTAMP NULL,
                                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                            )");
                            $db->execute();
                        } catch (\Exception $e) {}

                        $token = bin2hex(random_bytes(32));
                        $token_hash = hash('sha256', $token);

                        // Simpan token ke db
                        $db->query("INSERT INTO password_resets (user_id, token_hash, expires_at)
                                      VALUES (:user_id, :token_hash, (NOW() + INTERVAL 1 HOUR))");
                        $db->bind(':user_id', $user['id']);
                        $db->bind(':token_hash', $token_hash);
                        $db->execute();

                        $db->query("SELECT expires_at FROM password_resets WHERE token_hash = :token_hash ORDER BY id DESC LIMIT 1");
                        $db->bind(':token_hash', $token_hash);
                        $expiresRow = $db->single();
                        $expires = $expiresRow['expires_at'] ?? null;

                        // Hapus token lama/expired
                        $db->query("DELETE FROM password_resets WHERE expires_at <= NOW() OR used_at IS NOT NULL");
                        $db->execute();

                        // Load config & mailer
                        $config = require BASE_PATH . 'config/mail_config.php';
                        require_once BASE_PATH . 'config/mailer.php';

                        $link = BASE_URL . "/auth/reset_password?token=" . rawurlencode($token);
                        $body = "Halo {$user['nama']},\n\n"
                              . "Klik link berikut untuk reset password:\n"
                              . "{$link}\n\n"
                              . "Link ini berlaku 1 jam.\n";

                        $err = null;
                        $ok = smtp_send_mail($config, $user['email'], 'Reset Password Admin', $body, $err);

                        $db->query("INSERT INTO password_reset_email_logs (email, status, error_message, token_hash, expires_at) VALUES (:email, :status, :error_message, :token_hash, :expires_at)");
                        $db->bind(':email', $user['email']);
                        $db->bind(':status', $ok ? 'sent' : 'failed');
                        $db->bind(':error_message', $ok ? null : $err);
                        $db->bind(':token_hash', $token_hash);
                        $db->bind(':expires_at', $expires);
                        $db->execute();
                    }
                    
                    $pesan = 'Jika email terdaftar, link reset sudah dikirim.';
                }
            }
        }

        $this->view('admin/forgot_password', ['pesan' => $pesan]);
    }

    public function reset_password() {
        if (!session_id()) session_start();

        $pesan = '';
        $success = false;

        $token = $_GET['token'] ?? $_POST['token'] ?? '';
        $token = is_string($token) ? trim($token) : '';
        if ($token !== '') {
            $parts = preg_split('/\s+/', $token);
            $token = $parts[0] ?? $token;
        }
        $token = strtolower($token);

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                $pesan = 'Invalid security token. Please try again.';
            } else {
                $password = $_POST['password'] ?? '';
                $confirm = $_POST['confirm'] ?? '';

                if ($password === '' || $password !== $confirm) {
                    $pesan = 'Password tidak cocok.';
                } else {
                    $token_hash = hash('sha256', $token);
                    $db = new \Core\Database();
                    $db->query("SELECT * FROM password_resets WHERE token_hash = :token_hash AND used_at IS NULL AND expires_at > NOW() ORDER BY id DESC LIMIT 1");
                    $db->bind(':token_hash', $token_hash);
                    $row = $db->single();

                    if ($row) {
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        $db->query("UPDATE users SET password = :password WHERE id = :id");
                        $db->bind(':password', $hash);
                        $db->bind(':id', $row['user_id']);
                        $db->execute();

                        $db->query("UPDATE password_resets SET used_at = NOW() WHERE id = :id");
                        $db->bind(':id', $row['id']);
                        $db->execute();

                        $db->query("DELETE FROM password_resets WHERE expires_at <= NOW() OR used_at IS NOT NULL");
                        $db->execute();

                        // Log reset password
                        try {
                            $db->query("CREATE TABLE IF NOT EXISTS password_reset_logs (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                user_id INT NOT NULL,
                                ip_address VARCHAR(64),
                                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                            )");
                            $db->execute();

                            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                            $db->query("INSERT INTO password_reset_logs (user_id, ip_address) VALUES (:user_id, :ip_address)");
                            $db->bind(':user_id', $row['user_id']);
                            $db->bind(':ip_address', $ip);
                            $db->execute();
                        } catch (\Exception $e) {}

                        $pesan = 'Password berhasil direset. Silakan login.';
                        $success = true;
                    } else {
                        $pesan = 'Token tidak valid atau sudah kadaluarsa.';
                    }
                }
            }
        }

        $this->view('admin/reset_password', ['pesan' => $pesan, 'success' => $success, 'token' => $token]);
    }

    public function logout() {
        if (!session_id()) session_start();
        session_destroy();
        $this->redirect('/auth/login');
    }
}
