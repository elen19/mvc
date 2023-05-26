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

class ProjectController extends AbstractController
{
    #[Route('/proj', name: 'proj_home')]
    public function home(): Response
    {
        return $this->render('project/home.html.twig');
    }

    #[Route('/proj/game', name: 'proj_game')]
    public function game(Request $request): Response
    {
        // Retrieve the player's name from the request
        $playerName = $request->get('player_name');

        // Generate a new deck of cards
        $deck = new DeckOfCards();
        $deck->shuffle();

        // Deal initial cards to the player and computer
        $playerHand = new CardHand();
        $computerHand = new CardHand();

        for ($i = 0; $i < 5; $i++) {
            $playerHand->addCard($deck->draw());
            $computerHand->addCard($deck->draw());
        }

        // Render the game template with the necessary data
        return $this->render('project/game.html.twig', [
            'player_name' => $playerName,
            'player_hand' => $playerHand->getCards(),
            'computer_hand' => $computerHand->getCards(),
        ]);
    }
}
