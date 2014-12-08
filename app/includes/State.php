<?php

class State {

    public $current_document_id;
    public $current_archive_date;
    public $current_category_id;
    public $current_posts;
    private $app, $prismic;

    public function __construct(Slim\Slim $app, PrismicHelper $prismic)
    {
        $this->app = $app;
        $this->prismic = $prismic;
    }

    function current_query()
    {
        return $this->app->request()->params('q');
    }

    function current_page() {
        $pageQuery = $this->app->request()->params('page');
        return $pageQuery == null ? '1' : $pageQuery;
    }

    function current_document(PrismicHelper $prismic) {
        if ($this->current_document_id == null) {
            return null;
        }
        return $prismic->get_document($this->current_document_id);
    }

    function current_category(PrismicHelper $prismic) {
        if ($this->current_category_id == null) {
            return null;
        }
        return $prismic->get_document($this->current_category_id);
    }

    function current_response(PrismicHelper $prismic) {
        if (!$this->current_posts) {
            if ($this->current_query() != null) {
                // Search page
                $this->current_posts = $prismic->search($this->current_query(), $this->current_page());
            } else if ($this->current_archive_date() != null) {
                // Archive page
                $this->current_posts = $prismic->archives($this->current_archive_date(), $this->current_page());
            } else if ($this->current_category_id != null) {
                // Category page
                $this->current_posts = $prismic->category($this->current_category_id, $this->current_page());
            } else if ($this->current_document($prismic) && $this->current_document($prismic)->getType() == "author") {
                // Author page
                $this->current_posts = $prismic->byAuthor($this->current_document_id, $this->current_page());
            } else if ($this->current_document_id != null) {
                // Single page/post
                $this->current_posts = $prismic->single($this->current_document_id);
            } else {
                // Index page
                $this->current_posts = $prismic->get_posts($this->current_page());
            }
        }
        return $this->current_posts;
    }

    function current_posts() {
        return array_map(function($doc) {
            return BlogDocument::fromPrismicDoc($doc, $this->prismic);
        }, $this->current_response($this->prismic)->getResults());
    }

    function total_pages() {
        return $this->current_response($this->prismic)->getTotalPages();
    }

    function set_current_archive($year, $month, $day) {
        $this->current_archive_date = array(
            'year' => $year,
            'month' => $month,
            'day' => $day
        );
    }

    function current_archive_date() {
        return $this->current_archive_date;
    }

}
