<?php

use Prismic\Document;

class Post extends BlogDocument
{
    private $author;

    function getExcerpt()
    {
        if ($this->document->getStructuredText('post.shortlede')) {
            return $this->document->getStructuredText('post.shortlede')->asHtml($this->prismic->linkResolver);
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
        return $this->prismic->document_categories($this->document);
    }

}