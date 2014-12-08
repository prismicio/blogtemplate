<?php

function is_home()
{
    global $WPGLOBAL;
    $app = $WPGLOBAL['app'];
    return $app->request()->getUrl() == '/';
}

function get_previous_posts_link($label = '« Previous Page') {
    global $WPGLOBAL;
    $app = $WPGLOBAL['app'];
    $state = $WPGLOBAL['state'];
    if ($state->current_page() == 1) {
        return "";
    }
    $qs = $app->request()->params();
    $qs['page'] = ($state->current_page() - 1);
    $url = $app->request->getPath() . '?' . http_build_query($qs);
    return '<a href="' . $url . '">' . htmlentities($label) . '</a>';
}

function previous_posts_link($label = '« Previous Page') {
    echo get_previous_posts_link($label);
}

function get_next_posts_link($label = 'Next Page »') {
    global $WPGLOBAL;
    $app = $WPGLOBAL['app'];
    $state = $WPGLOBAL['state'];
    if ($state->current_page() >= $state->total_pages()) {
        return "";
    }
    $qs = $app->request()->params();
    $qs['page'] = ($state->current_page() + 1);
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

function wp_nav_menu($args) {
    $p = array_merge(array(
        'menu_class' => null,
        'menu_id' => null,
        'container' => 'div',
        'container_class' => null,
        'container_id' => null
    ), $args);
    function cls($c) { return $c ? ' class="' . $c . '"' : ''; }
    function id($id) { return $id ? ' id="' . $id . '"' : ''; }

    echo '<' . $p['container'] . cls($p['container_class']) . id($p['container_id']) . '>';
    echo '<ul' . cls($p['menu_class']) . id($p['menu_id']) . '>';
    echo '<li>' . home_link('Home') . '</li>';
    foreach(get_pages() as $page) {
        if(count($page['children']) > 0) {
            echo '<li>' . page_link($page) . '<ul>';
            foreach($page['children'] as $subpage) {
                echo page_link($subpage);
            }
            echo '</ul></li>';
        } else {
            echo '<li>' . page_link($page) . '</li>';
        }
    }

    echo '</ul></' . $p['container'] . '>';
}

