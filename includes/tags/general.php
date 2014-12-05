<?php

$home_link = new Twig_SimpleFunction('home_link', function ($label = 'Home') {
    global $app;
    $url = $app->request()->getUrl() . '/';
    return '<a href="' . $url . '">' . $label . '</a>';
}, array('is_safe' => array('html')));

$link = new Twig_SimpleFilter('link', function ($page) {
    return '<a href="' . $page->getPermalink() . '" class="' . ($page->isActive() ? 'active' : '') . '">' . $page->getTitle() . '</a>';
}, array('is_safe' => array('html')));


Theme::twig()->addFunction($home_link);
Theme::twig()->addFilter($link);


