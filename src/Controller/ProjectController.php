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
    #[Route('/proj/start', name: 'proj_start')]
    public function index(Request $request): Response
    {
        // Handle form submission to start the game
        if ($request->isMethod('POST')) {
            $numPlayers = $request->request->getInt('num_players');
            $session = $request->getSession();
            $session->set('num_players', $numPlayers);

            return $this->redirectToRoute('enter_name');
        }

        return $this->render('project/index.html.twig');
    }

    #[Route('/proj/names', name: 'enter_name')]
    public function startGame(Request $request, SessionInterface $session): Response
    {
        if ($session->has('num_players') && !$session->has('players')) {
            $numPlayers = $session->get('num_players');
            $session->set('players', []);
            return $this->render('project/add_name.html.twig', ['numPlayers' => $numPlayers]);
        }
    
        if ($request->isMethod('POST')) {
            $playerNames = $request->request->all()['players'];
            $players = [];
    
            foreach ($playerNames as $index => $playerName) {
                $player = [
                    'number' => $index,
                    'name' => $playerName,
                    'hand' => new CardHand()
                ];
                $players[] = $player;
            }
    
            $session->set('players', $players);
    
            return $this->redirectToRoute('proj_game');
        }
    
        return $this->render('proj/index.html.twig');
    }


    #[Route('/proj/game', name: 'proj_game')]
    public function game(Request $request, SessionInterface $session): Response
    {
        // Retrieve necessary game state from session
        $numPlayers = $session->get('num_players', 1);
        $players = $session->get('players', []);
        $deck = $session->get('deck');
        $dealerHand = $session->get('dealer_hand');
        $gameEnded = $session->get('game_ended', false);
        $winningPlayers = $session->get('winning_players', []);

        // Handle player actions
        if ($request->isMethod('POST')) {
            $playerIndex = $request->request->getInt('player_index');
            $action = $request->request->get('action');

            // Redirect to the game route
            return $this->redirectToRoute('proj_game');
        }

        return $this->render('project/game.html.twig', [
            'players' => $players,
            'numPlayers' => $numPlayers,
            'deck' => $deck,
            'dealerHand' => $dealerHand,
            'gameEnded' => $gameEnded,
            'winningPlayers' => $winningPlayers,
        ]);
    }
}
