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
        // Mock a call to index with 'fruit' type
        $this->client->request('GET', '/api/produce', ['type' => ProduceEnum::FRUIT->value]);

        // Assert the response status code
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Assert the response format (assuming JSON)
        $this->assertJson($this->client->getResponse()->getContent());

        // Decode and inspect response data (modify based on your data structure)
        $responseData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertArrayHasKey('name', $responseData[0]);
        $this->assertArrayHasKey('weight', $responseData[0]);
    }

    public function testAdd(): void
    {
        // Define data for a new item to add
        $type = ProduceEnum::FRUIT;

        $data = [
            'type' => $type->value,
            'name' => 'Apple',
            'weight' => 150
        ];


        // Send a POST request to the add endpoint
        $this->client->request('POST', '/api/produce', [], [], [], json_encode($data));

        // Assert the response status code
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        // Verify the response message or data
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('success', $responseData);
        $this->assertEquals($responseData['success'], "Added to {$type->value} collection");
    }

    public function testInvalidAdd(): void
    {

        // Define invalid data (missing 'name')
        $data = [
            'type' => ProduceEnum::FRUIT->value,
            'weight' => 150
        ];

        // Send a POST request with incomplete data
        $this->client->request('POST', '/api/produce', [], [], [], json_encode($data));
        // Assert a bad request status due to validation error
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        // Check for a descriptive error message
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('Name and weight must be provided', $responseData['error']);
    }
}
