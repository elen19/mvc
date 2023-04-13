<?php

namespace App\Controller;

use App\Cards\Card;
use App\Cards\CardGraphic;
use App\Cards\CardHand;
use App\Cards\DeckOfCards;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ApiController
{
    #[Route("/api/deck", name: "apiDeck", methods: ['GET'])]
    public function apiDeck(SessionInterface $session): Response 
    {
        if ($session->has("deck")) {
            $deck = $session->get("deck");
        } else {
            $deck = new DeckOfCards();
            $session->set("deck", $deck);
        }
        $deck->sort();

        $cards = $deck->getCards();

        $fixedCards = array();

        for ($i = 0; $i<$deck->getNrOfCards(); $i++) {
            $fixedCards[] = $cards[$i]->getSymbol();
        }

        $data = [
            'deck' => $fixedCards,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "apiShuffle", methods: ['POST'])]
    public function shuffle(SessionInterface $session): Response 
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set("deck", $deck);

        $cards = $deck->getCards();

        $fixedCards = array();

        for ($i = 0; $i<$deck->getNrOfCards(); $i++) {
            $fixedCards[] = $cards[$i]->getSymbol();
        }

        $data = [
            'deck' => $fixedCards,
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
