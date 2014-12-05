<?php

use Prismic\Document;

class Post extends BlogDocument
{
    public $document;
    private $author;

    function getExcerpt()
    {
        if ($this->document->getStructuredText('post.shortlede')) {
            return $this->document->getStructuredText('post.shortlede')->asHtml(PrismicHelper::$linkResolver);
        }
        // Plain text to avoid open tag at the end
        $body = $this->document->getStructuredText('post.body');
        if (strlen($body->asText()) > 300) {
            return substr($body->asText(), 0, 300) . "...";
        } else {
            return $body->asText();
        }
    }

    public function getCategories()
    {
        return PrismicHelper::document_categories($this->document);
    }

}