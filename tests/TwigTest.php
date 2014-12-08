<?php

class TwigTests extends LocalWebTestCase
{
    public function testVersion()
    {
        $this->client->get('/');
        $this->assertEquals(200, $this->client->response->status());

        $html = str_get_dom($this->client->response->body());
        $this->assertEquals(1, count($html('div.blog-post')));
    }
}
