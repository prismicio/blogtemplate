<?php

function is_day() {
    global $WPGLOBAL;
    $state = $WPGLOBAL['state'];
    return $state->current_archive_date && $state->current_archive_date['day'];
}

function is_month() {
    global $WPGLOBAL;
    $state = $WPGLOBAL['state'];
    return $state->current_archive_date && !is_day() && $state->current_archive_date['month'];
}

function is_year() {
    global $WPGLOBAL;
    $state = $WPGLOBAL['state'];
    return $state->current_archive_date && !is_month() && !is_day();
}

function archive_date() {
    global $WPGLOBAL;
    $state = $WPGLOBAL['state'];
    if (is_year()) {
        return $state->current_archive_date['year'];
    }
    if (is_month()) {
        $dt = DateTime::createFromFormat('!Y-m', $state->current_archive_date['year'] . '-' . $state->current_archive_date['month']);
        return $dt->format('F Y');
    }
    if (is_day()) {
        echo $state->current_archive_date['year']
        . '-' . $state->current_archive_date['month']
        . '-' . $state->current_archive_date['day'];
        $dt = DateTime::createFromFormat('!Y-m-d',
            $state->current_archive_date['year']
            . '-' . $state->current_archive_date['month']
            . '-' . $state->current_archive_date['day']);
        return $dt->format('F jS, Y');
    }
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
