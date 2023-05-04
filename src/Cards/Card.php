<?php

namespace App\Cards;

/**
 * Represents a playing card.
 */
class Card
{
    /**
     * The rank of the card.
     *
     * @var string
     */
    private string $rank;

    /**
     * The suit of the card.
     *
     * @var string
     */
    private string $suit;

    /**
     * Creates a new Card object with the specified rank and suit.
     *
     * @param string $rank The rank of the card.
     * @param string $suit The suit of the card.
     */
    public function __construct(string $rank, string $suit)
    {
        $this->rank = $rank;
        $this->suit = $suit;
    }

    /**
     * Returns the rank of the card.
     *
     * @return string The rank of the card.
     */
    public function getRank(): string
    {
        return $this->rank;
    }

    /**
     * Sets the rank of the card.
     *
     * @param string $rank The rank of the card.
     */
    public function setRank(string $rank): void
    {
        $this->rank = $rank;
    }

    /**
     * Returns the suit of the card.
     *
     * @return string The suit of the card.
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * Sets the suit of the card.
     *
     * @param string $suit The suit of the card.
     */
    public function setSuit(string $suit): void
    {
        $this->suit = $suit;
    }
}
