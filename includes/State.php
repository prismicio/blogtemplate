<?php

class State {

    static $current_document_id;
    static $current_archive_date;
    static $current_category;

    static function current_query()
    {
        global $app;
        return $app->request()->params('q');
    }

    static function current_page() {
        global $app;
        $pageQuery = $app->request()->params('page');
        return $pageQuery == null ? '1' : $pageQuery;
    }

    static function current_document() {
        if (State::$current_document_id == null) {
            return null;
        }
        return PrismicHelper::get_document(State::$current_document_id);
    }

    static function set_current_archive($year, $month, $day) {
        State::$current_archive_date = array(
            'year' => $year,
            'month' => $month,
            'day' => $day
        );
    }

    static function current_archive_date() {
        return State::$current_archive_date;
    }

}
