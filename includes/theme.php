<?php

class Theme {

    public static function directory() {
        return 'themes/' . PI_THEME;
    }

    public static function directory_url() {
        return '/' . Theme::directory();
    }

    public static function render($name) {
        include Theme::directory() . '/' . $name . '.php';
    }

}