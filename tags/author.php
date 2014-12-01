<?php
require 'vendor/autoload.php';

function author($document = null) {
    $doc = $document ? $document : current_document();
    if ($doc == null) return null;
    $docLink = $doc->getLink($doc->getType() . ".author");
    if ($docLink) {
        return PrismicHelper::get_document($docLink->getId());
    }
    return null;
}

function author_name($author = null) {
    $auth = $author ? $author : author();
    if (!$auth) return null;
    return htmlentities($auth->getText("author.full_name"));
}

function author_link($author = null) {
    $auth = $author ? $author : author();
    if (!$auth) return null;
    $author_link = PrismicHelper::$linkResolver->resolveDocument($auth);
    return '<a href = "' . $author_link . '">' . author_name($author) . '</a>';
}

function list_authors()
{

}

