<?php

namespace App\Controller;

use App\Cards\DeckOfCards;
use App\Cards\Card;
use App\Cards\CardGraphic;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class ApiController
{
    #[Route('/api/deck', name: 'apiDeck', methods: ['GET'])]
    public function apiDeck(SessionInterface $session): Response
    {
        if ($session->has('deck') == false) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }
        $deck = $session->get('deck');

        if ($deck instanceof DeckOfCards) {
            $deck->sort();
            $cards = $deck->getCards();


            $fixedCards = [];

            for ($i = 0; $i < $deck->getNrOfCards(); ++$i) {
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
        $data = [
            'deck' => "Error in session.",
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('/api/deck/shuffle', name: 'apiShuffle', methods: ['POST'])]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);

        $cards = $deck->getCards();

        $fixedCards = [];

        for ($i = 0; $i < $deck->getNrOfCards(); ++$i) {
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

    #[Route('/card/deck/draw', name: 'apiDraw', methods: ['POST'])]
    public function draw(SessionInterface $session): Response
    {
        if ($session->has('deck') == false) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }
        $deck = $session->get('deck');

        if ($deck instanceof DeckOfCards) {
            if (0 !== $deck->getNrOfCards()) {
                $card = $deck->draw();
                $session->set('card', $card);
            }
            if ($deck->getNrOfCards() > 0) {
                $card = $session->get('card');

                if ($card instanceof CardGraphic) {
                    $amount = $deck->getNrOfCards();
                    $data = [
                        'card' => $card->getSymbol(),
                        'cards left' => $amount,
                    ];

                    $response = new JsonResponse($data);
                    $response->setEncodingOptions(
                        $response->getEncodingOptions() | JSON_PRETTY_PRINT
                    );

                    return $response;
                }
            }
        }
        $data = [
            'card' => "Problem with session.",
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: 'apiDrawCards', methods: ['POST'])]
    public function drawCards(int $num, SessionInterface $session): Response
    {
        if ($session->has('deck') == false) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }
        $deck = $session->get('deck');

        if ($deck instanceof DeckOfCards) {
            if ($num > $deck->getNrOfCards()) {
                throw new Exception('Can not draw that many cards.');
            }
            $cards = [];
            for ($i = 0; $i < $num; ++$i) {
                $cards[] = $deck->draw()->getSymbol();
            }
            $amount = $deck->getNrOfCards();

            $data = [
                'cards' => $cards,
                'cards left' => $amount,
            ];

            $response = new JsonResponse($data);
            $response->setEncodingOptions(
                $response->getEncodingOptions() | JSON_PRETTY_PRINT
            );

            return $response;
        }
        $data = [
            'cards' => "Error in session.",
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );

        return $response;
    }
}
