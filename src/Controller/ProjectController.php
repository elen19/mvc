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
    #[Route('/proj', name: 'proj')]
    public function proj(Request $request): Response
    {
        return $this->render('project/home.html.twig');
    }

    #[Route('/proj/about', name: 'proj_about')]
    public function about(Request $request): Response
    {
        return $this->render('project/about.html.twig');
    }

    #[Route('/proj/start', name: 'proj_start')]
    public function index(Request $request, SessionInterface $session): Response
    {
        // Handle form submission to start the game
        if ($request->isMethod('POST')) {
            $numPlayers = (int) $request->request->get('num_players');
            $session->set('num_players', $numPlayers);
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set('deck', $deck);

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

            $dealerHand = new CardHand();
            if($session->has('deck')) {
                $deck = $session->get('deck');
                if ($deck instanceof DeckOfCards) {
                    for ($i = 0; $i < 2; $i++) {
                        foreach ($players as $index => $player) {
                            $card = $deck->draw();
                            $players[$index]['hand']->addCard($card);
                        }
                        $card = $deck->draw();
                        $dealerHand->addCard($card);
                    }
                }
            }
            $session->set('dealer_hand', $dealerHand);
            $session->set('players', $players);

            return $this->redirectToRoute('place_bet');
        }

        return $this->render('proj/index.html.twig');
    }


    #[Route('/proj/game', name: 'proj_game')]
    public function game(SessionInterface $session): Response
    {
        // Retrieve necessary game state from session
        $numPlayers = $session->get('num_players', 1);
        $players = $session->get('players', []);
        $deck = $session->get('deck');
        $dealerHand = $session->get('dealer_hand');
        $gameEnded = $session->get('game_ended', false);
        $winningPlayers = $session->get('winning_players', []);
        $currentPlayerIndex = $session->get('current_player_index', 0);

        if($session->get('game_ended') && !$dealerHand->getStay()) {
            while ($dealerHand->blackJackHand() < 16) {
                $card = $deck->draw();
                $dealerHand->addCard($card);
            }
            $updatedPlayers = [];
            foreach ($players as $player) {
                if (($player['hand']->blackJackHand() >= $dealerHand->blackJackHand() || $dealerHand->blackJackHand()>21) &&
                    $player['hand']->blackJackHand() <= 21) {
                    $winningPlayers[] = $player;
                    $player['hand']->calculateWinnings(1.5);
                }
                if ($player['hand']->getMoney() === 0) {
                    continue; // Skip the player with zero money
                }

                $player['number'] = count($updatedPlayers); // Update the 'number' value
                $updatedPlayers[] = $player;
            }

            $players = $updatedPlayers;
            $numPlayers = count($players);
            $session->set('players', $players);
            $session->set('num_players', $numPlayers);
            $session->set('deck', $deck);
            $session->set('dealer_hand', $dealerHand);
            $session->set('winning_players', $winningPlayers);
        }

        return $this->render('project/game.html.twig', [
            'players' => $players,
            'numPlayers' => $numPlayers,
            'deck' => $deck,
            'dealerHand' => $dealerHand,
            'gameEnded' => $gameEnded,
            'winningPlayers' => $winningPlayers,
            'currentPlayerIndex' => $currentPlayerIndex,
        ]);
    }

    #[Route('/proj/player-action', name: 'player_action')]
    public function playerAction(Request $request, SessionInterface $session): Response
    {
        // Retrieve necessary game state from session
        $players = $session->get('players', []);
        $playerIndex = $request->request->getInt('player_index');
        $action = $request->request->get('action');

        // Handle player action
        $player = $players[$playerIndex];

        if ($action === 'stay') {
            $player['hand']->stay();
        } elseif ($action === 'hit') {
            $deck = $session->get('deck');
            $card = $deck->draw();
            $player['hand']->addCard($card);
            if ($player['hand']->blackJackHand() > 20) {
                $player['hand']->stay();
            }
        }

        // Update the session with the modified player
        $players[$playerIndex] = $player;
        $session->set('players', $players);

        // Move to the next player
        $numPlayers = $session->get('num_players', 1);
        $nextPlayer = false;
        $loops = 0;
        $currentPlayerIndex = $playerIndex;
        while (!$nextPlayer && $loops < $numPlayers) {
            $currentPlayerIndex = ($currentPlayerIndex + 1) % $numPlayers;
            if (!$players[$currentPlayerIndex]['hand']->getStay()) {
                $nextPlayer = true;
            }
            $loops += 1;
        }

        if ($nextPlayer === false) {
            $session->set('game_ended', true);
        }
        $session->set('current_player_index', $currentPlayerIndex);

        // Redirect to the game route
        return $this->redirectToRoute('proj_game');
    }

    #[Route('/proj/reset', name: 'proj_reset')]
    public function resetSession(SessionInterface $session): Response
    {
        // Clear all session data
        $session->clear();

        // Redirect to the start
        return $this->redirectToRoute('proj_start');
    }

    #[Route('/proj/next-round', name: 'next_round')]
    public function nextRound(SessionInterface $session): Response
    {
        // Reset the game state
        $session->remove('deck');
        $session->remove('winning_players');
        $session->remove('game_ended');

        $players = $session->get('players', []);
        var_dump($players);
        foreach ($players as $player) {
            $player['hand']->clearHand();
        }

        $deck = new DeckOfCards();
        $deck->shuffle();
        $dealerHand = new CardHand();
        for ($i = 0; $i < 2; $i++) {
            foreach ($players as $index => $player) {
                $card = $deck->draw();
                $players[$index]['hand']->addCard($card);
            }
            $card = $deck->draw();
            $dealerHand->addCard($card);
        }
        $session->set('dealer_hand', $dealerHand);
        $session->set('deck', $deck);
        $session->set('players', $players);
        $session->set('current_player_index', 0);

        // Redirect to the game route to start a new round
        return $this->redirectToRoute('place_bet');
    }

    #[Route('/proj/bet', name: 'place_bet')]
    public function placeBet(Request $request, SessionInterface $session): Response
    {
        $players = $session->get('players', []);
        if ($request->isMethod('POST')) {
            $bets = $request->request->all()['bets'];

            foreach ($players as $index => $player) {
                $bet = (int) $bets[$index];
                if ($player['hand']->placeBet($bet)) {
                    $players[$index] = $player;
                }
            }

            $session->set('players', $players);

            // Redirect to the game route to start the round
            return $this->redirectToRoute('proj_game');
        }

        return $this->render('project/place_bet.html.twig', ['players' => $players]);
    }
}
