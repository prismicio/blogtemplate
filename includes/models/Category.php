<?php

use Prismic\Document;

class Category
{
    public $document;

    public function __construct(Document $doc)
    {
        $this->document = $doc;
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
        return PrismicHelper::$linkResolver->resolveDocument($this->document);
    }

    public static function fromId($docId)
    {
        $doc = PrismicHelper::get_document($docId);
        if (!$doc || $doc->getType() != "category") return null;
        return new Category($doc);
    }

}

