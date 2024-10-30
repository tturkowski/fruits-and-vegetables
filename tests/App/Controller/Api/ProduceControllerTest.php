<?php

namespace Tests\App\Controller\Api;

use App\DataFixtures\AppFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Enum\ProduceEnum;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ProduceControllerTest extends WebTestCase
{
    use ResetDatabase, Factories;

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $entityManager = self::getContainer()->get('doctrine')->getManager();

        $fixture = new AppFixtures();
        $fixture->load($entityManager);
    }

    public function testIndex(): void
    {
        $this->client->request('GET', '/api/produce', ['type' => ProduceEnum::FRUIT->value]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertJson($this->client->getResponse()->getContent());

        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('name', $responseData[0]);
        $this->assertArrayHasKey('weight', $responseData[0]);
    }

    public function testAdd(): void
    {
        $type = ProduceEnum::FRUIT;

        $data = [
            'type' => $type->value,
            'name' => 'Apple',
            'weight' => 150
        ];

        $this->client->request('POST', '/api/produce', [], [], [], json_encode($data));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertEquals($responseData['success'], "Added to {$type->value} collection");
    }

    public function testInvalidAdd(): void
    {
        $data = [
            'type' => ProduceEnum::FRUIT->value,
            'weight' => 150
        ];

        $this->client->request('POST', '/api/produce', [], [], [], json_encode($data));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('Name must be a string and weight must be a numeric value', $responseData['error']);
    }
}
