<?php
declare(strict_types=1);

namespace App\Tests\App\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $client->request('POST', '/api/product', content: file_get_contents('request.json'));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}