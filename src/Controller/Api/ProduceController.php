<?php

namespace App\Controller\Api;

use App\Enum\ProduceEnum;
use App\Service\CollectionService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduceController extends AbstractController
{
    public function __construct(
        private CollectionService $collectionService
    ) {}

    #[Route('/api/produce', name: 'api_produce_index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $typeParam = $request->query->get('type');

        if (!ProduceEnum::tryFrom($typeParam)) {
            return new JsonResponse(['error' => 'Invalid or missing type parameter'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $type = ProduceEnum::from($typeParam);

        $filters = [
            'name' => $request->query->get('name'),
            'weight' => $request->query->get('weight'),
        ];

        $filters = array_filter($filters, fn($value) => !is_null($value));

        $data = $this->collectionService->getCollection($type, $filters);
        
        return new JsonResponse($data);
    }

    #[Route('/api/produce', name: 'api_produce_add', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $type = null;

        if (!$type = ProduceEnum::tryFrom($data['type'])) {
            return new JsonResponse(['error' => 'Invalid type'], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!isset($data['name']) || !isset($data['weight']) || !is_string($data['name']) || !is_numeric($data['weight'])) {
            return new JsonResponse(['error' => 'Name must be a string and weight must be a numeric value'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->collectionService->addToCollection($type, $data['name'], $data['weight']);

        return new JsonResponse(['success' => "Added to {$type->value} collection"], JsonResponse::HTTP_CREATED);
    }

    // This is just to make it easy to test on the docs page
    // Collection Service Test contains tests for the service method
    #[Route('/api/produce/json', name: 'api_produce_json', methods: ['POST'])]
    public function jsonRequest()
    {
        $jsonFilePath = __DIR__ . '/../../../request.json';

        $this->collectionService->processJsonRequest($jsonFilePath);

        // Show all collection data to prove the results were parsed to the correct collection.
        $data = array_merge(
            $this->collectionService->getCollection(ProduceEnum::FRUIT),
            $this->collectionService->getCollection(ProduceEnum::VEGETABLE)
        );

        return new JsonResponse($data);
    }
}
