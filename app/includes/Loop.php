<?php

class Loop {

    public $loop_index = -1;
    private $prismic;
    private $state;

    public function __construct(PrismicHelper $prismic, State $state)
    {
        $this->prismic = $prismic;
        $this->state = $state;
    }

    function reset() {
        $this->loop_index = -1;
    }

    function increment() {
        $this->loop_index += 1;
    }

    function has_more() {
        // -1 because we check before incrementing
        return $this->loop_index < ($this->state->current_response($this->prismic)->getResultsSize() - 1);
    }

    function current_post() {
        $posts = $this->state->current_posts($this->prismic);
        if ($this->loop_index < 0 || $this->loop_index >= count($posts)) {
            return null;
        }
        return $posts[$this->loop_index];
    }

    function current_author() {
        if ($this->current_post()) {
            return $this->current_post()->getAuthor($this->prismic);
        } else return null;
    }

}
