<?php

namespace Contest\ContestBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageControllerTest extends WebTestCase
{
    public function testUploadimage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/{slug}/upload/image');
    }

}
