<?php

function filter_link($app, $input, $separator = '')
{
    if ($input == null) {
        return null;
    }
    if ($input instanceof NavMenuItem) {
        return '<a href="' . $input->getPermalink() . '" class="' . ($input->isActive($app) ? 'active' : '') . '">' . $input->getTitle() . '</a>';
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
    $home_link = new Twig_SimpleFunction('home_link', function ($label = 'Home') {
        $url = '/';
        return '<a href="' . $url . '">' . $label . '</a>';
    }, array('is_safe' => array('html')));

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

    $twig->addFunction($home_link);
    $twig->addFilter($link);
    $twig->addFunction($previous_posts_link);
    $twig->addFunction($next_posts_link);
}


