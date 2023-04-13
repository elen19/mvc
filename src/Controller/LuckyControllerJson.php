<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyControllerJson
{
    #[Route('/api/lucky/number')]
    public function jsonNumber(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'lucky-number' => $number,
            'lucky-message' => 'Hi there!',
        ];

        // return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route('/api/quote', name: 'quote')]
    public function quote(): Response
    {
        $number = random_int(0, 2);

        $quotes = [
            '0' => 'Never regret thy fall, O Icarus of the fearless flight For the greatest tragedy of them all Is never to feel the burning light. -Oscar Wilde',
            '1' => 'A bad plan is better than no plan at all. -Unknown origin',
            '2' => 'You Either Die a Hero, or You Live Long Enough To See Yourself Become the Villain. - Harvey Dent',
        ];

        $date = date('Y-m-d H:i:s');

        $data = [
            'quote' => $quotes[$number],
            'date' => $date,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
