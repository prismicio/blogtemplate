<?php

function filter_link($app, $input, $separator = '')
{
    if ($input == null) {
        return null;
    }
    if ($input instanceof NavMenuItem) {
        $classes = array();
        if ($input->isActive($app)) array_push($classes, 'active');
        if ($input->isExternal()) array_push($classes, 'external');
        return '<a href="' . $input->getPermalink() . '" class="' . join(' ', $classes) . '">' . $input->getTitle() . '</a>';
    }
    if ($input instanceof BlogDocument) {
        return '<a href="' . $input->getPermalink() . '">' . $input->getTitle() . '</a>';
    }
    if (is_array($input)) {
        // Return an array of string, then the join filter can be used
        return join($separator, array_map(function ($elt) use ($app, $separator) {
            return filter_link($app, $elt, $separator);
        }, $input));
    }
}

function register_tags(Slim\Slim $app, $twig, PrismicHelper $prismic, State $state)
{

    $link = new Twig_SimpleFilter('link', function ($input, $separator = '') use($app) {
        return filter_link($app, $input, $separator);
    }, array('is_safe' => array('html')));

    $previous_posts_link = new Twig_SimpleFunction('previous_posts_link', function ($label = '« Previous Page') use($app, $state) {
        if ($state->current_page() == 1) {
            return "";
        }
        $qs = $app->request()->params();
        $qs['page'] = ($state->current_page() - 1);
        $url = $app->request->getPath() . '?' . http_build_query($qs);
        return '<a href="' . $url . '">' . htmlentities($label) . '</a>';
    }, array('is_safe' => array('html')));

    $next_posts_link = new Twig_SimpleFunction('next_posts_link', function ($label = 'Next Page »') use($app, $prismic, $state) {
        if ($state->current_page() >= $state->total_pages($prismic)) {
            return "";
        }
        $qs = $app->request()->params();
        $qs['page'] = ($state->current_page() + 1);
        $url = $app->request->getPath() . '?' . http_build_query($qs);
        return '<a href="' . $url . '">' . htmlentities($label) . '</a>';
    }, array('is_safe' => array('html')));

    $nav_menu = new Twig_SimpleFunction('nav_menu', function () use($app, $prismic, $state) {
        $home = NavMenuItem::home($prismic);
        $result = "<ul>";
        $result .= "<li class='blog-nav-item'>" . filter_link($app, $home) . "</li>";
        if ($home) {
            foreach ($home->getChildren() as $page) {
                if (count($page->getChildren()) == 0) {
                    $result .= '<li class="blog-nav-item" >' . filter_link($app, $page) . '</li>';
                } else {
                    $result .= '<li class="blog-nav-item dropdown" >' . filter_link($app, $page);
                    $result .= '<ul class="dropdown-menu" >';
                    foreach ($page->getChildren() as $subpage) {
                        $result .= filter_link($app, $subpage);
                    }
                    $result .= '</ul>';
                    $result .= '</li>';
                }
            }
        }
        $result .= '</ul>';
        return $result;
    }, array('is_safe' => array('html')));

    $search_form = new Twig_SimpleFunction('search_form', function($placeholder = "Search...") {
        return '<form method="get" action = "search" >'
        . '<input type="text" placeholder="Search..." name="q">'
        . '</form >';
    }, array('is_safe' => array('html')));

    $calendar = new Twig_SimpleFunction('calendar', function () use($prismic) {
        return $prismic->get_calendar();
    });

    $twig->addFilter($link);
    $twig->addFunction($previous_posts_link);
    $twig->addFunction($next_posts_link);
    $twig->addFunction($calendar);
    $twig->addFunction($nav_menu);
    $twig->addFunction($search_form);
}


