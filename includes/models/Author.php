<?php

use Prismic\Document;

class Author
{
    public $document;

    public function __construct(Document $doc)
    {
        $this->document = $doc;
    }

    public function getName()
    {
        return $this->document->getText("author.full_name");
    }

    public function getPermalink()
    {
        return PrismicHelper::$linkResolver->resolveDocument($this->document);
    }

    public static function fromId($docId)
    {
        $doc = PrismicHelper::get_document($docId);
        if (!$doc || $doc->getType() != "author") return null;
        return new Author($doc);
    }

}