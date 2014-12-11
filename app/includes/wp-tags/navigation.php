<?php

function is_home()
{
    global $WPGLOBAL;
    $app = $WPGLOBAL['app'];
    return $app->request()->getUrl() == '/';
}

function get_previous_posts_link($label = '« Previous Page') {
    return 'TODO';
    /* global $WPGLOBAL;
    $app = $WPGLOBAL['app'];
    $state = $WPGLOBAL['state'];
    if ($state->current_page() == 1) {
        return "";
    }
    $qs = $app->request()->params();
    $qs['page'] = ($state->current_page() - 1);
    $url = $app->request->getPath() . '?' . http_build_query($qs);
    return '<a href="' . $url . '">' . htmlentities($label) . '</a>';*/
}

function previous_posts_link($label = '« Previous Page') {
    echo get_previous_posts_link($label);
}

function get_next_posts_link($label = 'Next Page »') {
    return 'TODO';
/*    global $WPGLOBAL;
    $app = $WPGLOBAL['app'];
    $state = $WPGLOBAL['state'];
    if ($state->current_page() >= $state->total_pages()) {
        return "";
    }
    $qs = $app->request()->params();
    $qs['page'] = ($state->current_page() + 1);
    $url = $app->request->getPath() . '?' . http_build_query($qs);
    return '<a href="' . $url . '">' . htmlentities($label) . '</a>';*/
}

function next_posts_link($label = 'Next Page »') {
    echo get_next_posts_link($label);
}

function previous_post_link($format = '&laquo; %link', $link = '%title', $in_same_term = false, $excluded_terms = '', $taxonomy = 'category') {
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    $loop = $WPGLOBAL['loop'];
    $previous = $prismic->previous($loop->current_post());
    if ($previous) {
        $url = $prismic->linkResolver->resolveDocument($previous);
        $label = str_replace('%link', htmlentities($previous->getText('post.title')), $format);
        echo '<a href="' . $url . '">' . $label . '</a>';
    }
}

function next_post_link($format = '%link &raquo;', $link = '%title', $in_same_term = false, $excluded_terms = '', $taxonomy = 'category') {
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    $loop = $WPGLOBAL['loop'];
    $next = $prismic->next($loop->current_post());
    if ($next) {
        $url = $prismic->linkResolver->resolveDocument($next);
        $label = str_replace('%link', htmlentities($next->getText('post.title')), $format);
        echo '<a href="' . $url . '">' . $label . '</a>';
    }
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
        if (count($page->getChildren()) > 0) {
            echo '<li>' . page_link($page) . '<ul>';
            foreach($page->getChildren() as $subpage) {
                echo page_link($subpage);
            }
            echo '</ul></li>';
        } else {
            echo '<li>' . page_link($page) . '</li>';
        }
    }

    echo '</ul></' . $p['container'] . '>';
}

function get_day_link($year, $month, $day)
{
    $now = new DateTime('now');
    if (!$year) $year = $now->format('Y');
    if (!$month) $month = $now->format('m');
    if (!$day) $day = $now->format('j');
    $date = DateTime::createFromFormat('Y-m-d', $year . '-' . $month . '-' . $day);
    $label = date_format($date, "F, jS Y");
    $url = archive_link($year, $month, $date->format('d'));
    return '<a href="' . $url . '">' . $label . '</a>';
}
