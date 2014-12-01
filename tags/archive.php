<?php
require 'vendor/autoload.php';


function is_day() {
    return State::$current_archive_date && State::$current_archive_date['day'];
}

function is_month() {
    return State::$current_archive_date && !is_day() && State::$current_archive_date['month'];
}

function is_year() {
    return State::$current_archive_date && !is_month() && !is_day();
}

function archive_date() {
    if (is_year()) {
        return State::$current_archive_date['year'];
    }
    if (is_month()) {
        $dt = DateTime::createFromFormat('!Y-m', State::$current_archive_date['year'] . '-' . State::$current_archive_date['month']);
        return $dt->format('F Y');
    }
    if (is_day()) {
        $dt = DateTime::createFromFormat('!Y-m-d',
            State::$current_archive_date['year']
            . '-' . State::$current_archive_date['month']
            . '-' . State::$current_archive_date['day']);
        return $dt->format('F jS, Y');
    }
}
