<?php

class Controller {
    
    protected function model($model) {
        require_once 'models/' . $model . '.php';
        return new $model();
    }
    
    protected function view($view, $data = []) {
        extract($data);
        require_once 'views/' . $view . '.php';
    }
    
    protected function redirect($url) {
        header('Location: ' . '/index.php?url=' . $url);
        exit();
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}

