<?php
require_once(realpath(dirname(__FILE__) . "/../../resources/config.php"));
require_once(APP_PATH . '/core/Controller.php');

class HomeController extends Controller {

    public function indexAction() {
        $this->view('home/index');
    }
}

?>
