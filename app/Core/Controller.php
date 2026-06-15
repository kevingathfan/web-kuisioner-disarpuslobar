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
        header('Location: ' . BASE_URL . $url);
        exit;
    }
}
