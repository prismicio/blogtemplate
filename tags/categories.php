<?php

function single_cat_title($prefix = '', $display = true)
{
    $category = State::current_category();
    $result = null;
    if ($category) {
        $result = $prefix . $category->getText('category.name');
    }
    if ($display) {
        echo $result;
    } else {
        return $result;
    }
}