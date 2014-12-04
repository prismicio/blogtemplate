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

require 'tags/general.php';
require 'tags/navigation.php';
require 'tags/posts.php';
require 'tags/pages.php';
require 'tags/author.php';
require 'tags/archive.php';
require 'tags/categories.php';
require 'tags/stubs.php';

if(file_exists('themes/' . PI_THEME . '/functions.php')) {
    // Optional helpers that theme developers can provide
    include 'themes/' . PI_THEME . '/functions.php';
}

