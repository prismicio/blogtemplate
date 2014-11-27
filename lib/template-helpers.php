<?php
require 'vendor/autoload.php';
require_once 'config.php';
require_once 'lib/prismic.php';

use Prismic\Predicates;

function get_header() {
    include('themes/' . PI_THEME . '../header.php');
}

function get_footer() {
    include('themes/' . PI_THEME . '../footer.php');
}

function get_pages() {
    $api = get_api();
    return $api->forms()
        ->everything
        ->query(Predicates::at("document.type", "page"))
        ->ref($api->master())
        ->submit()
        ->getResults();
}

function get_url_for($doc) {
    global $linkResolver;
    return $linkResolver->resolveDocument($doc);
}
