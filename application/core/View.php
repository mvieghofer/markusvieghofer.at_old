<?php

require_once(realpath(dirname(__FILE__) . '/../../resources/config.php'));

class View {
    
    public $navItems;
    
    public function renderContent($template, $data) {
        require_once(TEMPLATES_PATH . "/header.php");
        require_once(TEMPLATES_PATH . "/navigation.php");
        if (file_exists($template)) {
            require_once($template);
        } else {
            require_once(TEMPLATES_PATH . "/error.php");
        }
        
        require_once(TEMPLATES_PATH . "/footer.php");
    }
}

?>