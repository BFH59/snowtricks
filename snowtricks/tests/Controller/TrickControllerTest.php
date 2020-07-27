<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TrickControllerTest extends WebTestCase
{
    public function testTrick()
    {
        $client = static::createClient();

        $pageData = $client->request('GET', '/trick/figure-rotation-big-foot');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        //devrait retourner OK si une seule iframe
        $this->assertSame(1, $pageData->filter('iframe.myIframe')->count());
    }
}