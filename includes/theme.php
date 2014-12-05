<?php

class Theme {

    private static $twig;

    private static function twig() {
        if (!Theme::$twig) {
            Twig_Autoloader::register();

            $loader = new Twig_Loader_Filesystem(Theme::directory());
            Theme::$twig = new Twig_Environment($loader, array(
                // 'cache' => '/path/to/compilation_cache',
            ));
        }
        return Theme::$twig;
    }

    public static function directory() {
        return 'themes/' . PI_THEME;
    }

    public static function directory_url() {
        return '/' . Theme::directory();
    }

    public static function render($name) {
        if (Theme::isWP()) {
            include Theme::directory() . '/' . $name . '.php';
        } else {
            echo Theme::twig()->render($name . '.html.twig', array(
                "posts" => State::current_posts()
            ));
        }
    }

    private static function isWP() {
        return file_exists('themes/' . PI_THEME . '/index.php');
    }

}

