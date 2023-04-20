<?php

namespace App\Cards;

use Exception;

class DeckOfCards
{
    /**
    * @var CardGraphic[]
    */
    private array $cards;

    public function __construct()
    {
        $this->cards = [];
        $ranks = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];
        $suits = ['H', 'D', 'C', 'S'];

        foreach ($suits as $suit) {
            foreach ($ranks as $rank) {
                $card = new CardGraphic($rank, $suit);
                $this->cards[] = $card;
            }
        }
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }

    public function sort(): void
    {
        // Define the order of the suits
        $suitOrder = ['C', 'D', 'H', 'S'];

        // Define the order of the ranks
        $rankOrder = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];

        // Create a new array to hold the sorted deck
        $sortedDeck = [];

        // Iterate over the suits in order
        foreach ($suitOrder as $suit) {
            // Iterate over the ranks in order
            foreach ($rankOrder as $rank) {
                // Look for a card in the deck with the current suit and rank
                foreach ($this->cards as $key => $card) {
                    if ($card->getRank() == $rank && $card->getSuit() == $suit) {
                        // Add the card to the sorted deck and remove it from the original deck
                        $sortedDeck[] = $card;
                        unset($this->cards[$key]);
                        break;
                    }
                }
            }
        }

        $this->cards = $sortedDeck;
    }

    public function draw(): CardGraphic
    {
        if (empty($this->cards)) {
            throw new Exception('Deck is empty.');
        }

        return array_shift($this->cards);
    }

    public function getNrOfCards(): int
    {
        return count($this->cards);
    }

    /**
    * @return CardGraphic[]
    */
    public function getCards(): array
    {
        return $this->cards;
    }
}
