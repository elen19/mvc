<?php

namespace App\Cards;

class Card
{
    private string $rank;
    private string $suit;

    public function __construct(string $rank, string $suit)
    {
        $this->rank = $rank;
        $this->suit = $suit;
    }

    public function getRank(): string
    {
        return $this->rank;
    }

    public function setRank(string $rank): void
    {
        $this->rank = $rank;
    }

    public function getSuit(): string
    {
        return $this->suit;
    }

    public function setSuit(string $suit): void
    {
        $this->suit = $suit;
    }
}
