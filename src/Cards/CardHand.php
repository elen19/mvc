<?php

namespace App\Cards;

class CardHand
{
    private $cards;

    public function __construct()
    {
        $this->cards = [];
    }

    public function addCard(Card $card)
    {
        $this->cards[] = $card;
    }

    public function removeCard(Card $card)
    {
        $key = array_search($card, $this->cards);
        if (false !== $key) {
            unset($this->cards[$key]);
        }
    }

    public function getCards()
    {
        return $this->cards;
    }

    public function clear()
    {
        $this->cards = [];
    }
}
