<?php

namespace Contest\ContestBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    public function testDetails()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/details');
    }

}
