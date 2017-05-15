<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CookingControllerTest extends WebTestCase
{
    public function testStart()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/start');
    }

    public function testStop()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/stop');
    }

    public function testStatus()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/status');
    }

    public function testChangetemperature()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/changeTemperature');
    }

}
