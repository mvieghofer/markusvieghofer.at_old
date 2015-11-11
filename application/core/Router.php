<?php

require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));
require_once(DB_PATH);
    
class Router {
    private $controller = 'HomeController';
    
    private $errorMethod = 'errorAction';
    
    private $method = 'indexAction';
    
    private $params = [];
    
    private $urlMap = [];
    
    public function __construct() {
                
    }
    
    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = '/';
        if (isset($_GET['url'])) {
            $url .=  $_GET['url'];
        }
        foreach ($this->urlMap as $key => $value) {
            if (preg_match("/^$key$/", $url) && isset($value) && isset($value[$method])) {
                $this->mapUrl($url, $key, $value[$method]);
            }
        }
    }
    
    public function get($uri, $function) {
        $this->add($uri, $function, ['GET']);
    }
    
    public function post($uri, $function) {
        $this->add($uri, $function, ['POST']);
    }
    
    public function put($uri, $function) {
        $this->add($uri, $function, ['PUT']);
    }
    
    public function delete($uri, $function) {
        $this->add($uri, $function, ['DELETE']);
    }
    
    public function add($uri, $function, array $methods) {
        $uri = str_replace('/', '\/', $uri);
        if (!isset($this->urlMap[$uri]) || empty($this->urlMap[$uri])) {
            $this->urlMap[$uri] = [];
        }
        foreach($methods as $method) {
            $this->urlMap[$uri][$method] = $function;    
        }
    }
    
    protected function getControllerFileName($controllerName) {
        return APP_PATH . '/controllers/' . $controllerName . '.php';
    }
    
    protected function getControllerName($param) {
        return ucfirst($param) . 'Controller';
    }
    
    public static function getUrl($url) {
        if (substr($url, 0, 1) !== '/') {
            $url = '/' . $url;
        }
        return '' . $url;
    }
    
    public static function getAbsoluteUrl($url) {
        $url = Router::getUrl($url);
        return Config::$baseUrl . $url;
    }
    
    private function mapUrl($url, $key, $function) {
        if (is_string($function)) {
            $params = $this->getParamsFromUrl($url, $key);
            $params = $params ? array_values($params) : [];
            $function = explode('#', $function);
            require_once($this->getControllerFileName($function[0]));
            $controller = new $function[0];
            call_user_func_array([$controller, $function[1]], $params);
        } else {
            call_user_func($function);
        }
    }
    
    private function getParamsFromUrl($url, $regex) {
        if (isset($url) && isset($regex)) {
            $regex = str_replace('\/', '/', $regex);
            $url = explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));
            $regex = explode('/', filter_var(rtrim($regex, '/'), FILTER_SANITIZE_URL));
            if (sizeof($url) != sizeof($regex)) {
                return [];
            }
            foreach ($url as $index => $value) {
                if ($regex[$index] == $value) {
                    unset($url[$index]);
                }
            }
            return $url;
        }
    }
}
?>