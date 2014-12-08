<?php

function is_day() {
    global $WPGLOBAL;
    $state = $WPGLOBAL['state'];
    return $state->is_day();
}

function is_month() {
    global $WPGLOBAL;
    $state = $WPGLOBAL['state'];
    return $state->is_month();
}

function is_year() {
    global $WPGLOBAL;
    $state = $WPGLOBAL['state'];
    return $state->is_year();
}

function archive_date() {
    global $WPGLOBAL;
    $state = $WPGLOBAL['state'];
    return $state->current_archive_date_formatted();
}

function archive_link($year, $month = null, $day = null)
{
    global $WPGLOBAL;
    $prismic = $WPGLOBAL['prismic'];
    return $prismic->archive_link($year, $month, $day);
}
