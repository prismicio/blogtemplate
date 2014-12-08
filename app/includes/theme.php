<?php

class Theme {

    private static $twig;

    private static function themeName() {
        global $app;
        return $app->config('theme');
    }

    public static function twig() {
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
        return __DIR__ . '/../themes/' . Theme::themeName();
    }

    public static function directory_url() {
        return '/' . Theme::directory();
    }

    public static function render($name, $parameters = array()) {
        global $app;
        if (Theme::isWP()) {
            include Theme::directory() . '/' . $name . '.php';
        } else {
            echo Theme::twig()->render($name . '.html.twig', array_merge(array(
                "site_title" => $app->config('site.title'),
                "home" => NavMenuItem::home(),
                "posts" => State::current_posts()
            ), $parameters));
        }
    }

    private static function isWP() {
        return file_exists('themes/' . Theme::themeName() . '/index.php');
    }

}

