<?php

$home_link = new Twig_SimpleFunction('home_link', function ($label = 'Home') {
    global $app;
    $url = $app->request()->getUrl() . '/';
    return '<a href="' . $url . '">' . $label . '</a>';
}, array('is_safe' => array('html')));

function filter_link($input, $separator = '') {
     if ($input instanceof NavMenuItem) {
        return '<a href="' . $input->getPermalink() . '" class="' . ($input->isActive() ? 'active' : '') . '">' . $input->getTitle() . '</a>';
    }
    if ($input instanceof Category) {
        return '<a href="' . $input->getPermalink() . '">' . $input->getName() . '</a>';
    }
    if (is_array($input)) {
        // Return an array of string, then the join filter can be used
        return join($separator, array_map(function($elt) use($separator) { return filter_link($elt, $separator); }, $input));
    }
}

$link = new Twig_SimpleFilter('link', function ($input, $separator = '') {
    return filter_link($input, $separator);
}, array('is_safe' => array('html')));

$previous_posts_link = new Twig_SimpleFunction('previous_posts_link', function($label = '« Previous Page') {
    global $app;
    if (State::current_page() == 1) {
        return "";
    }
    $qs = $app->request()->params();
    $qs['page'] = (State::current_page() - 1);
    $url = $app->request->getPath() . '?' . http_build_query($qs);
    return '<a href="' . $url . '">' . htmlentities($label) . '</a>';
}, array('is_safe' => array('html')));

$next_posts_link = new Twig_SimpleFunction('next_posts_link', function ($label = 'Next Page »') {
    global $app;
    if (State::current_page() >= State::total_pages()) {
        return "";
    }
    $qs = $app->request()->params();
    $qs['page'] = (State::current_page() + 1);
    $url = $app->request->getPath() . '?' . http_build_query($qs);
    return '<a href="' . $url . '">' . htmlentities($label) . '</a>';
}, array('is_safe' => array('html')));

Theme::twig()->addFunction($home_link);
Theme::twig()->addFilter($link);
Theme::twig()->addFunction($previous_posts_link);
Theme::twig()->addFunction($next_posts_link);


