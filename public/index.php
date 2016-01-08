<?php

    require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));

    session_start();

    $controllerName = 'HomeController';
    $actionName = "indexAction";

    $url = [];
    if (isset($_GET['url'])) {
        if (endsWith($_GET['url'], '/')) {
            $url = '/' . rtrim($_GET['url'], '/');
            header("Location: {$url}");
        }
        foreach(explode('/', $_GET['url']) as $urlPart) {
            $url[] =  $urlPart;
        }
    }

    if (isset($url[0])) {
        $controllerName = ucfirst(rtrim($url[0], '/')) . 'Controller';
        unset($url[0]);
    }

    if (isset($url[1]) && !empty($url[1])) {
        $actionName = '';
        $actionParts = explode("-", $url[1]);
        foreach($actionParts as $actionPart) {
            $actionName .= ucfirst(strtolower($actionPart));
        }
        $actionName = lcfirst($actionName) . 'Action';
        unset($url[1]);
    }

    $fullControllerFileName = getFileName($controllerName);
    if (!file_exists($fullControllerFileName)) {
        $controllerName = "ErrorController";
        $fullControllerFileName = getFileName($controllerName);
        $actionName = "indexAction";
    }
    require_once($fullControllerFileName);

    $controllerName = new $controllerName;

    if (!method_exists($controllerName, $actionName)) {
        $controllerName = "ErrorController";
        $fullControllerFileName = getFileName($controllerName);
        $actionName = "indexAction";
        require_once($fullControllerFileName);
        $controllerName = new $controllerName;
    }

    call_user_func_array([$controllerName, $actionName], $url);

    function getFileName($controllerName) {
        return APP_PATH . '/controllers/' . $controllerName . '.php';
    }

    function endsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }
?>
