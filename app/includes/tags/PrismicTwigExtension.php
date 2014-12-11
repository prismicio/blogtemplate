<?php

/**
 * Class PrismicTwigExtension
 *
 * A set of filters and functions useful for Prismic developers using the Twig template engine
 */
class PrismicTwigExtension extends Twig_Extension
{

    private $linkResolver;

    public function __construct(\Prismic\LinkResolver $linkResolver)
    {
        $this->linkResolver = $linkResolver;
    }

    public function getName()
    {
        return 'PrismicExtension';
    }

    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('html', array($this, 'htmlFilter'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('text', array($this, 'textFilter'))
        );
    }

    public function htmlFilter($input)
    {
        return $input->asHtml($this->linkResolver);
    }

    public function textFilter($input)
    {
        return $input->asText();
    }

}
