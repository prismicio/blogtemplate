<?php

// config.php is not present by default, so we show a message explaining to create one
if (file_exists('app/config.php')) {
    include 'config.php';
} else {
    include 'includes/templates/firstrun.php';
    exit();
}

require 'includes/PrismicHelper.php';
require 'includes/State.php';
require 'includes/Loop.php';
require 'includes/theme.php';

require 'includes/models/BlogDocument.php';
require 'includes/models/Author.php';
require 'includes/models/Post.php';
require 'includes/models/Page.php';
require 'includes/models/NavMenuItem.php';
require 'includes/models/Category.php';

if (file_exists('app/themes/' . PI_THEME . '/index.php')) {
    // Wordpress Theme
    require 'includes/wp-tags/general.php';
    require 'includes/wp-tags/navigation.php';
    require 'includes/wp-tags/posts.php';
    require 'includes/wp-tags/pages.php';
    require 'includes/wp-tags/author.php';
    require 'includes/wp-tags/archive.php';
    require 'includes/wp-tags/categories.php';
    require 'includes/wp-tags/stubs.php';

    if (file_exists('themes/' . PI_THEME . '/functions.php')) {
        // Optional helpers that theme developers can provide
        include 'themes/' . PI_THEME . '/functions.php';
    }
} else {
    // Twig theme
    require 'includes/tags/general.php';
}

