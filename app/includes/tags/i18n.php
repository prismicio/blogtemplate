<?php

function init_i18n($app)
{
    if ($app->config('lang') != null) {
        $locale = $app->config('lang');
    } else {
        $locale = "en_US";
    }
    setlocale(LC_ALL, $locale);

    // Theme messages
    bindtextdomain('theme', theme_dir($app) . '/languages/' . $locale);
    echo 'path = ' . theme_dir($app) . '/languages/' . $locale;
}

function __($message, $domain = 'default')
{
    echo '<p style="color: red">Load [' . $message . '] for domain [' . $domain . ']</p>';
    return dgettext($domain, $message);
}

