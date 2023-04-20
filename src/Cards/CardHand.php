<?php

namespace App\Cards;

class CardHand
{
    /**
    * @var Card[]
    */
    private array $cards;

    public function __construct()
    {
        $this->cards = [];
    }

    public function addCard(Card $card): void
    {
        $this->cards[] = $card;
    }

    public function removeCard(Card $card): void
    {
        $key = array_search($card, $this->cards);
        if (false !== $key) {
            unset($this->cards[$key]);
        }
    }

    /**
    * @return Card[]
    */
    public function getCards(): array
    {
        return $this->cards;
    }

    public function clear(): void
    {
        $this->cards = [];
    }
}
