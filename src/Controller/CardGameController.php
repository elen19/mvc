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

class CardGameController extends AbstractController
{
    #[Route("/card", name: "card")]
    public function home(SessionInterface $session): Response 
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set("deck", $deck);
        return $this->render("cardGame/home.html.twig");
    }

    #[Route("/card/deck", name: "deck")]
    public function deck(SessionInterface $session): Response 
    {
        if ($session->has("deck")) {
            $deck = $session->get("deck");
        } else {
            $deck = new DeckOfCards();
            $session->set("deck", $deck);
        }
        $deck->sort();

        $cards = $deck->getCards();

        $suits = array('C', 'D', 'H', 'S');

        return $this->render('cardGame/deck.html.twig', [
            'cards' => $cards, 'suits' => $suits,
        ]);
    }

    #[Route("/card/deck/shuffle", name: "shuffle")]
    public function shuffle(SessionInterface $session): Response 
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set("deck", $deck);

        $cards = $deck->getCards();

        return $this->render('cardGame/shuffle.html.twig', [
            'cards' => $cards
        ]);
    }

    #[Route("/card/deck/draw", name: "draw")]
    public function draw(SessionInterface $session): Response 
    {
        if ($session->has("deck")) {
            $deck = $session->get("deck");
        } else {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set("deck", $deck);
        }

        if ($deck->getNrOfCards() !== 0)
        {
            $card = $deck->draw();
            $session->set("card", $card);
        } else {
            $card = $session->get("card");
        }
        $amount = $deck->getNrOfCards();

        return $this->render('cardGame/draw.html.twig', [
            'card' => $card, 'amount' => $amount,
        ]);
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: "drawCards")]
    public function drawCards(int $num, SessionInterface $session): Response 
    {
        if ($session->has("deck")) {
            $deck = $session->get("deck");
        } else {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set("deck", $deck);
        }

        if ($num > $deck->getNrOfCards()) {
            throw new \Exception("Can not draw that many cards.");
        }
        $cards = array();
        for ($i = 0; $i < $num; $i++) {
            $cards[] = $deck->draw();
        }
        $amount = $deck->getNrOfCards();

        return $this->render('cardGame/drawCards.html.twig', [
            'cards' => $cards, 'amount' => $amount,
        ]);
    }
}
