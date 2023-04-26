<?php

namespace App\Controller;

use App\Cards\DeckOfCards;
use App\Cards\CardHand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class CardGameController extends AbstractController
{
    #[Route('/card', name: 'card')]
    public function home(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);

        return $this->render('cardGame/home.html.twig');
    }

    #[Route('/card/deck', name: 'deck')]
    public function deck(SessionInterface $session): Response
    {
        if ($session->has('deck') == false) {
            $deck = new DeckOfCards();
            $session->set('deck', $deck);
        }
        $deck = $session->get('deck');
        if ($deck instanceof DeckOfCards) {
            $deck->sort();

            $cards = $deck->getCards();

            $suits = ['C', 'D', 'H', 'S'];

            return $this->render('cardGame/deck.html.twig', [
                'cards' => $cards, 'suits' => $suits,
            ]);
        }
        return $this->render('cardGame/deck.html.twig');
    }

    #[Route('/card/deck/shuffle', name: 'shuffle')]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set('deck', $deck);

        $cards = $deck->getCards();

        return $this->render('cardGame/shuffle.html.twig', [
            'cards' => $cards,
        ]);
    }

    #[Route('/card/deck/draw', name: 'draw')]
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
                $amount = $deck->getNrOfCards();

                return $this->render('cardGame/draw.html.twig', [
                    'card' => $card, 'amount' => $amount,
                ]);
            }
        }
        return $this->render('cardGame/draw.html.twig');
    }

    #[Route("/card/deck/draw/{num<\d+>}", name: 'drawCards')]
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
                $cards[] = $deck->draw();
            }
            $amount = $deck->getNrOfCards();

            return $this->render('cardGame/drawCards.html.twig', [
                'cards' => $cards, 'amount' => $amount,
            ]);
        }
        return $this->render('cardGame/drawCards.html.twig');
    }

    #[Route("/game", name: 'game')]
    public function game(): Response
    {
        return $this->render('cardGame/game.html.twig');
    }

    #[Route("/game/blackjack", name: 'blackjack')]
    public function blackjack(SessionInterface $session): Response
    {
        if ($session->has('deck') == false) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        }
        $deck = $session->get('deck');

        if (!$session->has('player') || !$session->has('dealer')) {
            if ($deck instanceof DeckOfCards) {
                $player = new CardHand();
                $dealer = new CardHand();
                for ($i = 0; $i < 4; $i++) {
                    $card = $deck->draw();
                    if ($i%2 == 0) {
                        $dealer->addCard($card);
                    }
                    if ($i%2 == 1) {
                        $player->addCard($card);
                    }
                }
                $session->set('dealer', $dealer);
                $session->set('player', $player);
                return $this->renderBlackJack($session);
            }
        }
        $player = $session->get('player');
        $dealer = $session->get('dealer');
        return $this->renderBlackJack($session);
    }

    #[Route("/game/blackjack/draw", name: 'blackjackDraw')]
    public function blackjackDraw(SessionInterface $session): Response
    {
        $deck = $session->get('deck') ?? new DeckOfCards();
        $player = $session->get('player');
        $dealer = $session->get('dealer');

        if ($player instanceof CardHand && $dealer instanceof CardHand && $deck instanceof DeckOfCards) {
            if ($player->blackJackHand() < 21 && !$player->getStay()) {
                $card = $deck->draw();
                $player->addCard($card);
            }

            if ($player->blackJackHand() > 21 || $player->getStay()) {
                $dealer = $this->playDealer($dealer, $deck);
            }
            $session->set('player', $player);
            $session->set('dealer', $dealer);
        }

        return $this->renderBlackJack($session);
    }

    private function playDealer(CardHand $dealer, DeckOfCards $deck): CardHand
    {
        while ($dealer->blackJackHand() < 17) {
            $card = $deck->draw();
            $dealer->addCard($card);
        }
        $dealer->stay();
        return $dealer;
    }

    private function renderBlackJack(SessionInterface $session): response
    {
        return $this->render('cardGame/blackjack.html.twig', ['player' => $session->get('player'), 'dealer' => $session->get('dealer')]);
    }

    #[Route("/game/stay", name: 'stay')]
    public function stay(SessionInterface $session): Response
    {
        if ($session->has('player')) {
            $player = $session->get('player');
            if ($player instanceof CardHand) {
                $player->stay();
            }
        }
        return $this->redirectToRoute('blackjackDraw');
    }

    #[Route("/game/clear", name: 'clear')]
    public function gameClear(SessionInterface $session): Response
    {
        $session->clear();
        return $this->redirectToRoute('blackjack');
    }
}
