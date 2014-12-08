<?php

use Prismic\Document;

class Author
{
    public $document;
    private $prismic;

    public function __construct(Document $doc, PrismicHelper $prismic)
    {
        $this->document = $doc;
        $this->prismic = $prismic;
    }

    public function getName()
    {
        return $this->document->getText("author.full_name");
    }

    public function getPermalink()
    {
        return $this->prismic->linkResolver->resolveDocument($this->document);
    }

    public static function fromId(PrismicHelper $prismic, $docId)
    {
        $doc = $prismic->get_document($docId);
        if (!$doc || $doc->getType() != "author") return null;
        return new Author($doc, $prismic);
    }

}