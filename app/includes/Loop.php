<?php

class Loop {

    public $loop_index = -1;
    private $prismic;
    private $posts;

    public function __construct(PrismicHelper $prismic)
    {
        $this->prismic = $prismic;
    }

    public function setPosts($posts) {
        $this->posts = $posts;
    }

    function reset() {
        $this->loop_index = -1;
    }

    function increment() {
        $this->loop_index += 1;
    }

    function has_more() {
        // -1 because we check before incrementing
        return $this->loop_index < (count($this->posts) - 1);
    }

    function current_post() {
        if ($this->loop_index < 0 || $this->loop_index >= count($this->posts)) {
            return null;
        }
        return $this->posts[$this->loop_index];
    }

    function current_author() {
        if ($this->current_post()) {
            return $this->current_post()->getAuthor($this->prismic);
        } else return null;
    }

}
