<?php

namespace App\Cards;

class Card
{
    private $rank;
    private $suit;

    public function __construct($rank, $suit)
    {
        $this->rank = $rank;
        $this->suit = $suit;
    }

    public function getRank()
    {
        return $this->rank;
    }

    public function setRank($rank)
    {
        $this->rank = $rank;
    }

    public function getSuit()
    {
        return $this->suit;
    }

    public function setSuit($suit)
    {
        $this->suit = $suit;
    }
}
