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
            new Twig_SimpleFilter('archivelink', array($this, 'archivelinkFilter'), array('is_safe' => array('html'))),
            new Twig_SimpleFilter('excerpt', array($this, 'excerptFilter'), array('is_safe' => array('html')))
        );
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('previous_posts_link', array($this, 'previousPostsLink'), array('is_safe' => array('html'))),
            new Twig_SimpleFunction('next_posts_link', array($this, 'nextPostsLink'), array('is_safe' => array('html'))),
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
        if ($input instanceof NavMenuItem) {
            $classes = array();
            if ($input->isActive($this->app)) array_push($classes, 'active');
            if ($input->isExternal()) array_push($classes, 'external');
            return '<a href="' . $input->getPermalink() . '" class="' . join(' ', $classes) . '">' . $input->getTitle() . '</a>';
        }
        if ($input instanceof BlogDocument) {
            return '<a href="' . $input->getPermalink() . '">' . $input->getTitle() . '</a>';
        }
        if ($input instanceof \Prismic\Document) {
            $url = $this->prismic->linkResolver->resolveDocument($input);
            $label = $input->getText($input->getType() . '.title');
            return '<a href="' . $url . '">' . $label . '</a>';
        }
        if (is_array($input)) {
            // Return an array of string, then the join filter can be used
            return join($separator, array_map(function ($elt) use ($separator) {
                return $this->linkFilter($elt, $separator);
            }, $input));
        }
    }

    function previousPostsLink($label = '« Previous Page') {
        return ''; // TODO
        /* if ($state->current_page() == 1) {
                return "";
            }
            $qs = $app->request()->params();
            $qs['page'] = ($state->current_page() - 1);
            $url = $app->request->getPath() . '?' . http_build_query($qs);
            return '<a href="' . $url . '">' . htmlentities($label) . '</a>'; */
    }

    function nextPostsLink($label = 'Next Page »') {
        return ''; // TODO
        /* if ($state->current_page() >= $state->total_pages($prismic)) {
                return "";
            }
            $qs = $app->request()->params();
            $qs['page'] = ($state->current_page() + 1);
            $url = $app->request->getPath() . '?' . http_build_query($qs);
            return '<a href="' . $url . '">' . htmlentities($label) . '</a>';*/
    }

    function navMenu () {
        $home = NavMenuItem::home($this->prismic);
        $result = "<ul>";
        $result .= "<li class='blog-nav-item'>" . $this->linkFilter($home) . "</li>";
        if ($home) {
            foreach ($home->getChildren() as $page) {
                if (count($page->getChildren()) == 0) {
                    $result .= '<li class="blog-nav-item" >' . $this->linkFilter($page) . '</li>';
                } else {
                    $result .= '<li class="blog-nav-item dropdown" >' . $this->linkFilter($page);
                    $result .= '<ul class="dropdown-menu" >';
                    foreach ($page->getChildren() as $subpage) {
                        $result .= $this->linkFilter($this->app, $subpage);
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
