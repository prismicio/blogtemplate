<?php

class Theme {

    private $twig;
    private $app;
    private $prismic;
    private $name;
    private $state;

    public function __construct(\Slim\Slim $app, PrismicHelper $prismic)
    {
        global $WPGLOBAL;
        $this->app = $app;
        $this->prismic = $prismic;
        $this->name = $app->config('theme');

        if ($this->isWP()) {
            $WPGLOBAL = array(
                'theme' => $this,
                'app' => $this->app,
                'prismic' => $this->prismic,
                'loop' => new Loop($this->prismic)
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
            $this->twig->addExtension(new PrismicTwigExtension($this->prismic->linkResolver));
            $this->twig->addExtension(new BlogTwigExtension($this->app, $this->prismic));
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
            $loop = $WPGLOBAL['loop'];
            if (isset($parameters['response'])) {
                $loop->setPosts($parameters['response']->getResults());
                $loop->page = $parameters['response']->getPage();
                $loop->totalPages = $parameters['response']->getTotalPages();
            } else if (isset($parameters['post'])) {
                $loop->setPosts(array($parameters['post']));
            } else if (isset($parameters['page'])) {
                $loop->setPosts(array($parameters['page']));
            } else if (isset($parameters['author'])) {
                $loop->setPosts(array($parameters['author']));
            }
            if (isset($parameters['date'])) {
                $WPGLOBAL['date'] = $parameters['date'];
            }
            if (isset($parameters['post'])) {
                $WPGLOBAL['single_post'] = $parameters['post'];
            } else if (isset($parameters['category'])) {
                $WPGLOBAL['single_post'] = $parameters['category'];
            }
            include $this->directory() . '/' . $name . '.php';
        } else {
            echo $this->twig()->render($name . '.html.twig', array_merge(array(
                "site_title" => $this->app->config('site.title'),
                "site_description" => $this->app->config('site.description'),
                "home" => $this->prismic->home()
            ), $parameters));
        }
    }

    private function isWP() {
        return file_exists($this->directory() . '/index.php');
    }

}

