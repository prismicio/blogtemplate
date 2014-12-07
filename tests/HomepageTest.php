<?php

class HomepageTests extends LocalWebTestCase
{
    public function testVersion()
    {
        $this->client->get('/');
        $this->assertEquals(200, $this->client->response->status());
        $this->assertEquals($this->app->config('version'), $this->client->response->body());
    }
}
