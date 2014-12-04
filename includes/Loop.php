<?php

class Loop {

    static $loop_index = -1;

    static function reset() {
        Loop::$loop_index = -1;
    }

    static function increment() {
        Loop::$loop_index += 1;
    }

    static function has_more() {
        // -1 because we check before incrementing
        return Loop::$loop_index < (State::current_posts()->getResultsSize() - 1);
    }

    static function current_post() {
        $posts = State::current_posts()->getResults();
        if (Loop::$loop_index < 0 || Loop::$loop_index >= count($posts)) {
            return null;
        }
        return $posts[Loop::$loop_index];
    }

    static function current_author_id() {
        $post = Loop::current_post();
        if ($post) {
            $author = $post->getLink($post->getType() . '.author');
            return $author ? $author->getId() : null;
        } else {
            return null;
        }
    }

}
