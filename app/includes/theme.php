<?php

define('ABSPATH', dirname(__FILE__) . '../');

class Theme {

    private $app;
    private $prismic;
    private $name;

    public function __construct(\Slim\Slim $app, PrismicHelper $prismic)
    {
        global $WPGLOBAL;
        $this->app = $app;
        $this->prismic = $prismic;
        $this->name = $app->config('theme');

        $GLOBALS['pagenow'] = '';
        $WPGLOBAL = array(
            'theme' => $this,
            'app' => $this->app,
            'prismic' => $this->prismic,
            'loop' => new Loop($this->prismic)
        );

        if (file_exists($this->directory() . '/functions.php')) {
            // Optional helpers that theme developers can provide
            require_once ($this->directory() . '/functions.php');
        }
    }

    public function directory() {
        return __DIR__ . '/../themes/' . $this->name;
    }

    public function directory_url() {
        return '/app/themes/' . $this->name;
    }

    public function render($name, $parameters = array()) {
        global $WPGLOBAL, $wp_query;
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
        if (isset($parameters['tag'])) {
            $WPGLOBAL['tag'] = $parameters['tag'];
        }
        if (isset($parameters['date'])) {
            $WPGLOBAL['date'] = $parameters['date'];
        }
        if (isset($parameters['post'])) {
            $WPGLOBAL['single_post'] = $parameters['post'];
        } else if (isset($parameters['category'])) {
            $WPGLOBAL['single_post'] = $parameters['category'];
        }
        if (isset($parameters['search_query'])) {
            $WPGLOBAL['search_query'] = $parameters['search_query'];
            $wp_query = new WP_Query($parameters['search_query']);
        } else {
            $wp_query = new WP_Query('');
        }
        include $this->directory() . '/' . $name . '.php';
    }

}

