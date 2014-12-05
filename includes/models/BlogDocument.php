<?php

use Prismic\Document;

abstract class BlogDocument
{
    public $document;
    private $author;

    public function __construct(Document $doc)
    {
        $this->document = $doc;
    }

    public function getTitle()
    {
        return $this->document->getText($this->document->getType() . ".title");
    }

    public function getBody()
    {
        $body = $this->document->getStructuredText($this->document->getType() . ".body");
        return $body ? $body->asHtml() : null;
    }

    public function getPermalink()
    {
        return PrismicHelper::$linkResolver->resolveDocument($this->document);
    }

    public function getDate()
    {
        $date = $this->document->getDate($this->document->getType() . ".date");
        return $date ? $date->asDateTime() : null;
    }

    public function getAuthor()
    {
        $authorLink = $this->document->getLink($this->document->getType() . ".author");
        if (!$authorLink) return null;
        return Author::fromId($authorLink->getId());
    }

    public static function fromPrismicDoc($document)
    {
        if (!$document) return null;
        switch ($document->getType()) {
            case "post": return new Post($document);
            case "author": return new Author($document);
            case "category": return new Category($document);
            case "page": return new Page($document);
        }
    }

    public static function fromId($docId)
    {
        return BlogDocument::fromPrismicDoc(PrismicHelper::get_document($docId));
    }

}