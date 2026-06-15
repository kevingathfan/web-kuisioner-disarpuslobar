<?php
namespace Core;

class Controller {
    public function view($view, $data = []) {
        extract($data);
        require_once BASE_PATH . 'app/Views/' . $view . '.php';
    }

    public function model($model) {
        require_once BASE_PATH . 'app/Models/' . $model . '.php';
        $fullClass = "\\Models\\" . $model;
        return new $fullClass();
    }

    public function redirect($url) {
        if (defined('USE_REWRITE') && !USE_REWRITE) {
            $cleanUrl = ltrim($url, '/');
            header('Location: ' . BASE_URL . '/public/index.php?url=' . $cleanUrl);
        } else {
            header('Location: ' . BASE_URL . $url);
        }
        exit;
    }
}
