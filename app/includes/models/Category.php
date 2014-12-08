<?php

use Prismic\Document;

class Category
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
        return $this->document->getText("category.name");
    }

    public function getDescription()
    {
        return $this->document->getStructuredText("category.description")->asHtml();
    }

    public function getParent()
    {
        // TODO
    }

    public function getPermalink()
    {
        return $this->prismic->linkResolver->resolveDocument($this->document);
    }

    public static function fromId($docId, PrismicHelper $prismic)
    {
        $doc = $prismic->get_document($docId);
        if (!$doc || $doc->getType() != "category") return null;
        return new Category($doc, $prismic);
    }

}

