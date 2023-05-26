<?php

namespace App\Controller;

use App\Cards\Card;
use App\Cards\CardGraphic;
use App\Cards\CardHand;
use App\Cards\DeckOfCards;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProjectController extends AbstractController
{
    #[Route('/proj', name: 'proj_home')]
    public function home(): Response
    {
        return $this->render('project/home.html.twig');
    }

    #[Route('/proj/game', name: 'proj_game')]
    public function game(Request $request, SessionInterface $session): Response
    {
        // Check if the player name is set in the session, otherwise redirect to the home page
        if (!$session->has('player_name')) {
            return $this->redirectToRoute('add_name');
        }

        // Check if the deck exists in session, otherwise create a new one
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);
        } else {
            $deck = $session->get('deck');
        }

        // Check if the player hand exists in session, otherwise deal initial cards
        if (!$session->has('player_hand')) {
            $playerHand = new CardHand();
            $this->dealInitialCards($playerHand, $deck, 5);
            $session->set('player_hand', $playerHand);
        } else {
            $playerHand = $session->get('player_hand');
        }

        // Check if the computer hand exists in session, otherwise deal initial cards
        if (!$session->has('computer_hand')) {
            $computerHand = new CardHand();
            $this->dealInitialCards($computerHand, $deck, 5);
            $session->set('computer_hand', $computerHand);
        } else {
            $computerHand = $session->get('computer_hand');
        }

        // Render the game view with the necessary data
        return $this->render('project/game.html.twig', [
            'playerHand' => $playerHand,
            'computerHand' => $computerHand,
            'playerName' => $session->get('player_name'),
        ]);
    }

    /**
     * Deals the specified number of cards from the deck to the given hand.
     *
     * @param CardHand $hand The hand to deal the cards to.
     * @param DeckOfCards $deck The deck of cards.
     * @param int $numCards The number of cards to deal.
     */
    private function dealInitialCards(CardHand $hand, DeckOfCards $deck, int $numCards): void
    {
        for ($i = 0; $i < $numCards; $i++) {
            $card = $deck->draw();
            $hand->addCard($card);
        }
    }

    /**
     * Returns an array of card choices for the form.
     *
     * @param CardHand $hand The player's hand.
     * @return array The card choices.
     */
    private function getCardChoices(CardHand $hand): array
    {
        $choices = [];
        foreach ($hand->getCards() as $index => $card) {
            $choices[$index] = $index;
        }
        return $choices;
    }

    /**
     * Exchanges the selected cards in the player's hand with new cards from the deck.
     *
     * @param CardHand $hand The player's hand.
     * @param DeckOfCards $deck The deck of cards.
     * @param array $cardsToExchange The indices of the cards to exchange.
     */
    private function exchangeCards(CardHand $hand, DeckOfCards $deck, array $cardsToExchange): void
    {
        foreach ($cardsToExchange as $index) {
            $card = $deck->draw();
            $hand->replaceCard($index, $card);
        }
    }

    /**
     * Deals replacement cards from the deck to the player's hand.
     *
     * @param CardHand $hand The player's hand.
     * @param DeckOfCards $deck The deck of cards.
     * @param int $numCards The number of replacement cards to deal.
     */
    private function dealReplacementCards(CardHand $hand, DeckOfCards $deck, int $numCards): void
    {
        for ($i = 0; $i < $numCards; $i++) {
            $card = $deck->draw();
            $hand->addCard($card);
        }
    }


    #[Route('/proj/add-name', name: 'add_name')]
    public function addName(SessionInterface $session): Response
    {
        if ($session->has('player_name')) {
            return $this->redirectToRoute('proj_game');
        }

        return $this->render('project/add_name.html.twig');
    }

    #[Route('/proj/start-game', name: 'start_game', methods: ['POST'])]
    public function startGame(Request $request, SessionInterface $session): Response
    {
        $playerName = $request->request->get('name');
        $session->set('player_name', $playerName);
        
        // Other game initialization logic
        
        return $this->redirectToRoute('proj_game');
    }
}
