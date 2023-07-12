<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Product\Storage\StocksService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class LoadController
{

    #[Route(path: '/upload', name: 'upload_data', methods: ['POST'])]
    public function loadData(Request $request, StocksService $stocksService): Response
    {
        //It is an example, not for production
        $stocks = $stocksService->process($request->getContent());

        ob_start();
        echo "<h2>fruits</h2>";
        var_dump($stocks->getFruits());

        $fruits = $stocks->getFruits();
        $fruitsItems = $fruits->list();
        $toDelete = 2;
        echo "<ul>";
        foreach ($fruitsItems as $fruit) {
            $removed = $fruits->remove($fruit->getId());
            echo "<li>Removed [$removed] fruits with id: {$fruit->getId()} </li>";
            if (!$toDelete--) {
                break;
            }
        }
        echo "</ul>";

        echo "<h2>fruits after deletion</h2>";
        var_dump($stocks->getFruits());

        echo "<h2>vegetables</h2>";
        var_dump($stocks->getVegetables());
        $data = ob_get_clean();

        return new Response("<h1>Processed:</h1>" . $data, Response::HTTP_OK);
    }

}
