<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Product;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class ProductController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer
    )
    {
    }

    #[Route('/product', methods: ['POST'])]
    #[Route('/product/', methods: ['POST'])]
    #[Route('/product/{measurement}', methods: ['POST'])]
    public function create(Request $request, string|null $measurement): Response
    {
        $data = $this->serializer->deserialize(
            data: $request->getContent(),
            type: 'App\Model\Product[]',
            format: 'json'
        );

        $productService = (new ProductService($data))->saveData();

        if (!in_array($measurement, Product::UNITS)) {
            $measurement = Product::UNIT_G;
        }


        return (new Response())->setContent(
            $this->serializer->serialize(
                data: $productService->getData($measurement),
                format: 'json'
            )
        );
    }
}