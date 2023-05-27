<?php

namespace App\Cards;

class CardHand
{
    /**
    * @var CardGraphic[]
    */
    private array $cards;

    private bool $stay;

    private int $bet;

    private int $money;

    public function __construct()
    {
        $this->cards = [];
        $this->stay = false;
        $this->bet = 0;
        $this->money = 100; 
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

    public function getBet(): int
    {
        return $this->bet;
    }

    public function setBet(int $bet): void
    {
        $this->bet = $bet;
    }

    public function getMoney(): int
    {
        return $this->money;
    }

    public function setMoney(int $money): void
    {
        $this->money = $money;
    }

    public function placeBet(int $bet): bool
    {
        if ($bet > $this->money) {
            return false; // Insufficient funds
        }

        $this->bet = $bet;
        $this->money -= $bet;
        return true;
    }

    public function calculateWinnings(float $multiplier): void
    {
        $winnings = $this->bet * $multiplier;
        $this->money += $winnings;
        $this->bet = 0;
    }

}
