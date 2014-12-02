<?php
require 'vendor/autoload.php';

class Theme {

    public static function render($name) {
        include 'themes/' . PI_THEME . '/' . $name . '.php';
    }

}