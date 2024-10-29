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

        // Get filter parameters from the request
        $filters = [
            'name' => $request->query->get('name'),
            'weight' => $request->query->get('weight'),
        ];

        // Remove any null values from filters
        $filters = array_filter($filters, fn($value) => !is_null($value));

        // Get the data from the collection service with filters
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

        if (!isset($data['name']) || !isset($data['weight'])) {
            return new JsonResponse(['error' => 'Name and weight must be provided'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->collectionService->addToCollection($type, $data['name'], $data['weight']);

        return new JsonResponse(['success' => "Added to {$type->value} collection"], JsonResponse::HTTP_CREATED);
    }
}
