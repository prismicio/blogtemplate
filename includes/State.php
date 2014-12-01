<?php
require '../vendor/autoload.php';


class State {

    static $current_document_id;

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

}
