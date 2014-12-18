<?php

class WP_Query {

    public $query;
    public $query_vars;
    public $queried_object;
    public $queried_object_id;
    public $posts;
    public $post_count;
    public $found_posts;
    public $max_num_pages;
    public $current_post;
    public $post;
    public $is_single;
    public $is_page;
    public $is_archive;
    public $is_preview;
    public $is_date;
    public $is_year;
    public $is_month;
    public $is_time;
    public $is_author;
    public $is_category;
    public $is_tag;
    public $is_tax;
    public $is_search;
    public $is_feed;
    public $is_comment_feed;
    public $is_trackback;
    public $is_home;
    public $is_404;
    public $is_comments_popup;
    public $is_admin;
    public $is_attachment;
    public $is_singular;
    public $is_robots;
    public $is_posts_page;
    public $is_paged;

    function __construct($query = '') {
        $this->query = $query;
    }

    function init() {
    }

    function parse_query( $query ) {
    }

    function parse_query_vars() {
    }

    function get( $query_var ) {
    }

    function set( $query_var, $value ) {
    }

    function &get_posts() {
    }

    function next_post() {
    }

    function have_posts() {
        // TODO
        return false;
    }

    function the_post() {
        // TODO
        return null;
    }

    function rewind_posts() {
    }

    function &query($query) {
    }

    function get_queried_object() {
    }

    function get_queried_object_id() {
    }

}