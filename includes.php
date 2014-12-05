<?php

require 'vendor/autoload.php';

// config.php is not present by default, so we show a message explaining to create one
if (file_exists('config.php')) {
    require_once 'config.php';
} else {
    include 'includes/templates/firstrun.php';
    exit();
}

require 'includes/PrismicHelper.php';
require 'includes/State.php';
require 'includes/Loop.php';
require 'includes/theme.php';

if (file_exists('themes/' . PI_THEME . '/index.php')) {
    // Wordpress Theme
    require 'wp-tags/general.php';
    require 'wp-tags/navigation.php';
    require 'wp-tags/posts.php';
    require 'wp-tags/pages.php';
    require 'wp-tags/author.php';
    require 'wp-tags/archive.php';
    require 'wp-tags/categories.php';
    require 'wp-tags/stubs.php';

    if (file_exists('themes/' . PI_THEME . '/functions.php')) {
        // Optional helpers that theme developers can provide
        include 'themes/' . PI_THEME . '/functions.php';
    }
} else {
    // Twig theme
    require 'tags/general.php';
}

