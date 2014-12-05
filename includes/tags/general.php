<?php

$home_link = new Twig_SimpleFunction('home_link', function ($label = 'Home') {
    global $app;
    $url = $app->request()->getUrl() . '/';
    return '<a href="' . $url . '">' . $label . '</a>';
}, array('is_safe' => array('html')));

$link = new Twig_SimpleFilter('link', function ($input, $separator = '') {
    global $link;
    if ($input instanceof NavMenuItem) {
        return '<a href="' . $input->getPermalink() . '" class="' . ($input->isActive() ? 'active' : '') . '">' . $input->getTitle() . '</a>';
    }
    if ($input instanceof Category) {
        return '<a href="' . $input->getPermalink() . '">' . $input->getName() . '</a>';
    }
    if (is_array($input)) {
        // Return an array of string, then the join filter can be used
        return join($separator, array_map($link->getCallable(), $input));
    }
}, array('is_safe' => array('html')));

Theme::twig()->addFunction($home_link);
Theme::twig()->addFilter($link);


