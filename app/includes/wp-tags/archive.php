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
    $url = '/' . $year;
    if ($month) {
        $url .= '/' . $month;
    }
    if ($month && $day) {
        $url .= '/' . $day;
    }
    return $url;
}
