<?php

function get_previous_posts_link($label = '« Previous Page') {
    global $app;
    if (State::current_page() == 1) {
        return "";
    }
    $qs = $app->request()->params();
    $qs['page'] = (State::current_page() - 1);
    $url = $app->request->getPath() . '?' . http_build_query($qs);
    return '<a href="' . $url . '">' . htmlentities($label) . '</a>';
}

function previous_posts_link($label = '« Previous Page') {
    echo get_previous_posts_link($label);
}

function get_next_posts_link($label = 'Next Page »') {
    global $app;
    if (State::current_page() >= State::total_pages()) {
        return "";
    }
    $qs = $app->request()->params();
    $qs['page'] = (State::current_page() + 1);
    $url = $app->request->getPath() . '?' . http_build_query($qs);
    return '<a href="' . $url . '">' . htmlentities($label) . '</a>';
}

function next_posts_link($label = 'Next Page »') {
    echo get_next_posts_link($label);
}

function previous_post_link() {
    // TODO
}

function next_post_link() {
    // TODO
}

function wp_link_pages($args) {
    // TODO
}