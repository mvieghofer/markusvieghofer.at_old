<?php
require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));
require_once(APP_PATH . '/core/Controller.php');
require_once(APP_PATH . '/core/db.php');

class ErrorController extends Controller {

    public function indexAction() {
        $this->view('error/404');
    }
}

?>
