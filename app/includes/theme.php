<?php

class Theme {

    private $twig;
    private $app;
    private $prismic;
    private $name;
    private $state;

    public function __construct(\Slim\Slim $app, State $state, PrismicHelper $prismic)
    {
        global $WPGLOBAL;
        $this->app = $app;
        $this->prismic = $prismic;
        $this->name = $app->config('theme');
        $this->state = $state;

        if ($this->isWP()) {
            $WPGLOBAL = array(
                'theme' => $this,
                'app' => $this->app,
                'prismic' => $this->prismic,
                'loop' => new Loop($this->prismic, $this->state),
                'state' => $this->state
            );
            // Wordpress tags
            require_once 'wp-tags/general.php';
            require_once 'wp-tags/navigation.php';
            require_once 'wp-tags/posts.php';
            require_once 'wp-tags/pages.php';
            require_once 'wp-tags/author.php';
            require_once 'wp-tags/archive.php';
            require_once 'wp-tags/categories.php';
            require_once 'wp-tags/stubs.php';
            if (file_exists($this->directory() . '/functions.php')) {
                // Optional helpers that theme developers can provide
                require_once ($this->directory() . '/functions.php');
            }
        }
    }

    public function twig() {
        if (!$this->twig) {
            Twig_Autoloader::register();

            $loader = new Twig_Loader_Filesystem($this->directory());
            $this->twig = new Twig_Environment($loader, array(
                // 'cache' => '/path/to/compilation_cache',
            ));
            // Twig tags
            require_once 'tags/general.php';
            register_tags($this->app, $this->twig, $this->prismic, $this->state);
        }
        return $this->twig;
    }

    public function directory() {
        return __DIR__ . '/../themes/' . $this->name;
    }

    public function directory_url() {
        return '/app/themes/' . $this->name;
    }

    public function render($name, $parameters = array()) {
        global $WPGLOBAL;
        if ($this->isWP()) {
            include $this->directory() . '/' . $name . '.php';
        } else {
            echo $this->twig()->render($name . '.html.twig', array_merge(array(
                "site_title" => $this->app->config('site.title'),
                "site_description" => $this->app->config('site.description'),
                "home" => NavMenuItem::home($this->prismic),
                "posts" => $this->state->current_posts($this->prismic),
                "search_query" => $this->state->current_query()
            ), $parameters));
        }
    }

    private function isWP() {
        return file_exists($this->directory() . '/index.php');
    }

}

