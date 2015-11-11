<?php

require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));
require_once(APP_PATH . '/core/View.php');

class Controller {
    public $view;
    
    public function __construct() {
        $this->view = new View();
        $this->view->basePath = Config::$baseUrl;
    }
    
    public function model($model) {
        $modelPath = APP_PATH . '/models/' . $model . '.php';
        if (file_exists($modelPath)) {
            require_once($modelPath);
            return new $model;
        }
    }
    
    public function view($view, $data = []) {
        $viewPath = APP_PATH . '/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            $this->renderView($viewPath, $data);
        }
    }
    
    protected function renderView($view, $data) {
        $this->view->renderContent($view, $data);
    }
    
    protected function updateCSRFToken($path = '/') {
        $token = md5(uniqid());
        setcookie(Config::$csrfTokenCookieName, $token, time() + 60 * 60 * 24, $path);
        return $token;
    }
    
    public function redirect($url) {
        header("Location: {$url}");
    }
    
    public function isLoggedId() {
        if (isset($_COOKIE[Config::loginCookieName])) {
            $loginCookie = json_decode($_COOKIE[Config::loginCookieName]);
            $user = User::where('token', '=', $loginCookie->token)->first();
            return $user->remember_until >= new DateTime();
        }
        return false;
    }
}
?>