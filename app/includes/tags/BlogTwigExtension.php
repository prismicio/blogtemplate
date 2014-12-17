<?php

class BlogTwigExtension extends Twig_Extension
{

    private $app;
    private $prismic;

    public function __construct(Slim\Slim $app, PrismicHelper $prismic) {
        $this->app = $app;
        $this->prismic = $prismic;
    }

    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('link', array($this, 'linkFilter'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('author', array($this, 'authorFilter'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('categories', array($this, 'categoriesFilter')),
            new Twig_SimpleFilter('archivelink', array($this, 'archivelinkFilter'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('excerpt', array($this, 'excerptFilter'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('previous_posts_link', array($this, 'previousPostsLink'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('next_posts_link', array($this, 'nextPostsLink'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('previous_post', array($this, 'previousPostFilter'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('next_post', array($this, 'nextPostFilter'), array('is_safe' => array('html')))
        );
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('nav_menu', array($this, 'navMenu'), array('is_safe' => array('html'))),
            new Twig_SimpleFunction('search_form', array($this, 'searchForm'), array('is_safe' => array('html'))),
            new Twig_SimpleFunction('calendar', array($this, 'calendar'), array('is_safe' => array('html')))
        );
    }

    public function linkFilter($input, $separator = '')
    {
        if ($input == null) {
            return null;
        }
        if (is_array($input) && isset($input['url'])) {
            $classes = array();
            $active = $this->app->request()->getPath() == $input['url'];
            if ($active) array_push($classes, 'active');
            if ($input['external'] == true) array_push($classes, 'external');
            return '<a href="' . $input['url'] . '" class="' . join(' ', $classes) . '">' . $input['label'] . '</a>';
        }
        if ($input instanceof \Prismic\Document) {
            $url = $this->prismic->linkResolver->resolveDocument($input);
            if ($input->getType() == "category") {
                $field = "category.name";
            } else if ($input->getType() == "author") {
                $field = "author.full_name";
            } else {
                $field = $input->getType() . '.title';
            }
            $label = $input->getText($field);
            return '<a href="' . $url . '">' . $label . '</a>';
        }
        if (is_array($input)) {
            // Return an array of string, then the join filter can be used
            return join($separator, array_map(function ($elt) use ($separator) {
                return $this->linkFilter($elt, $separator);
            }, $input));
        }
    }

    function previousPostFilter($post) {
        if ($post) {
            return $this->prismic->previous($post);
        }
        return null;
    }

    function nextPostFilter($post) {
        if ($post) {
            return $this->prismic->next($post);
        }
        return null;
    }

    function previousPostsLink($response, $label = '« Previous Page') {
        if ($response->getPrevPage() == null) {
            return "";
        }
        $qs = $this->app->request()->params();
        $qs['page'] = ($response->page - 1);
        $url = $this->app->request->getPath() . '?' . http_build_query($qs);
        return '<a href="' . $url . '">' . htmlentities($label) . '</a>';
    }

    function nextPostsLink($response, $label = 'Next Page »') {
        if ($response->getNextPage() == null) {
            return "";
        }
        $qs = $this->app->request()->params();
        $qs['page'] = ($response->page + 1);
        $url = $this->app->request->getPath() . '?' . http_build_query($qs);
        return '<a href="' . $url . '">' . htmlentities($label) . '</a>';
    }

    function navMenu () {
        $home = $this->prismic->home();
        $result = "<ul>";
        $result .= "<li class='blog-nav-item'>" . $this->linkFilter($home) . "</li>";
        if ($home) {
            foreach ($home['children'] as $page) {
                if (count($page['children']) == 0) {
                    $result .= '<li class="blog-nav-item" >' . $this->linkFilter($page) . '</li>';
                } else {
                    $result .= '<li class="blog-nav-item dropdown" >' . $this->linkFilter($page);
                    $result .= '<ul class="dropdown-menu" >';
                    foreach ($page['children'] as $subpage) {
                        $result .= $this->linkFilter($subpage);
                    }
                    $result .= '</ul>';
                    $result .= '</li>';
                }
            }
        }
        $result .= '</ul>';
        return $result;
    }

    function searchForm ($placeholder = "Search...") {
        return '<form method="get" action = "/search" >'
        . '<input type="text" placeholder="Search..." name="q">'
        . '</form >';
    }

    function calendar() {
        return $this->prismic->get_calendar();
    }

    public function authorFilter($document)
    {
        $authorLink = $document->getLink($document->getType() . ".author");
        if (!$authorLink) return null;
        $results = $this->prismic->single($authorLink->getId())->getResults();
        if (count($results) > 0) {
            return $results[0];
        }
        return null;
    }

    public function categoriesFilter($document)
    {
        return $this->prismic->document_categories($document);
    }

    function archivelinkFilter($date) {
        if ($date instanceof \Prismic\Fragment\Date) {
            $date = $date->asDateTime();
        }
        $url = $this->prismic->archive_link($date->format('Y'), $date->format('m'), $date->format('d'));
        return '<a href="' . $url . '">' . $date->format('F, jS Y') . '</a>';
    }

    function excerptFilter($document) {
        if ($document->getStructuredText('post.shortlede')) {
            return $document->getStructuredText('post.shortlede')->asHtml($this->prismic->linkResolver);
        }
        // Plain text to avoid open tag at the end
        $body = $document->getStructuredText('post.body');
        if (strlen($body->asText()) > 300) {
            return substr($body->asText(), 0, 300) . "...";
        } else {
            return $body->asText();
        }
    }

    public function getName()
    {
        return 'BlogExtension';
    }

}
