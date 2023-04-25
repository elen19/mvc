<?php

namespace App\Cards;

class CardHand
{
    /**
    * @var CardGraphic[]
    */
    private array $cards;

    private bool $stay;

    public function __construct()
    {
        $this->cards = [];
        $this->stay = false;
    }

    public function addCard(CardGraphic $card): void
    {
        if(!$this->stay) {
            $this->cards[] = $card;
        }
    }

    public function removeCard(CardGraphic $card): void
    {
        $key = array_search($card, $this->cards);
        if (false !== $key) {
            unset($this->cards[$key]);
        }
    }

    /**
    * @return CardGraphic[]
    */
    public function getCards(): array
    {
        return $this->cards;
    }

    public function clear(): void
    {
        $this->cards = [];
    }

    public function blackJackHand(): float
    {
        $sum = 0;

        foreach ($this->cards as $card) {
            $rank = $card->getRank();
            $sum += (is_numeric($rank)) ? $rank : ($rank == 'A' ? 11 : 10);
            if ($rank == 'A' && $sum > 21) {
                $sum -= 10;
            }
        }
        return $sum;
    }

    public function getStay(): bool
    {
        return $this->stay;
    }

    public function stay(): void
    {
        $this->stay = true;
    }
}
