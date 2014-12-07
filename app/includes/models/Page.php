<?php

use Prismic\Document;

class Page extends BlogDocument
{

    public function getBody()
    {
        $body = $this->document->getStructuredText("page.content");
        return $body ? $body->asHtml() : null;
    }

}