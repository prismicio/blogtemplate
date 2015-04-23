<?php

class T12Test extends LocalWebTestCase
{
    public function getConfig()
    {
        return array_merge(parent::getConfig(), array(
            'theme'          => 'twentytwelve',
        ));
    }

    public function testBlogHome()
    {
        $this->client->get('/');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
    }

    public function testPermalink()
    {
        $this->client->get('/blog/2014/11/27/first-post');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('#colophon')));
        $this->assertEquals(1, count($html('header.site-header')));
    }

    public function testArchive()
    {
        $this->client->get('/archive/2014/11');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('#colophon')));
        $this->assertEquals(1, count($html('header.site-header')));
    }

    public function testSearch()
    {
        $this->client->get('/search', array('q' => 'sample'));
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('#colophon')));
        $this->assertEquals(1, count($html('header.site-header')));
    }

    public function testSearchNoResult()
    {
        $this->client->get('/search', array('q' => 'asdfasdfkjheiudwed'));
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('#colophon')));
        $this->assertEquals(1, count($html('header.site-header')));
    }

    public function testAuthor()
    {
        $this->client->get('/author/VHiMRicAACcAHSaw/erwan-loisant');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('#colophon')));
        $this->assertEquals('My First Blog Post', trim($html('h1.entry-title', 0)->getPlainText()));
    }

    public function testCategory()
    {
        $this->client->get('/category/personal');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('#colophon')));
        $this->assertEquals(1, count($html('header.site-header')));
    }

    public function testPage()
    {
        $this->client->get('/starter');
        $this->assertEquals(200, $this->client->response->status());
        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('#colophon')));
        $this->assertEquals('Starter Project', trim($html('h1.entry-title', 0)->getPlainText()));
    }

    public function testSubPage()
    {
        $this->client->get('/starter/pages');
        $this->assertEquals(200, $this->client->response->status());
        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('#colophon')));
        $this->assertEquals('Pages', trim($html('h1.entry-title', 0)->getPlainText()));
    }

    public function testTag()
    {
        $this->client->get('/tag/sample');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('#colophon')));
        $this->assertEquals(1, count($html('article')));
    }
}
