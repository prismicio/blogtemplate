<?php

class WPTest extends LocalWebTestCase
{

    public function getConfig() {
        return array_merge(parent::getConfig(), array(
            'theme'          => 'wpbootstrap'
        ));
    }

    public function testHome()
    {
        $this->client->get('/');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('footer.blog-footer')));
        $this->assertEquals(1, count($html('div.blog-post')));
    }

    public function testPermalink()
    {
        $this->client->get('/2014/11/27/VHeiWScAACYA7RUF/my-first-blog');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('footer.blog-footer')));
        $this->assertEquals(1, count($html('div.blog-header')));
    }

    public function testArchive()
    {
        $this->client->get('/2014/11');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('footer.blog-footer')));
        $this->assertEquals(1, count($html('div.blog-post')));
    }

}
