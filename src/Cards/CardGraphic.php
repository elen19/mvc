<?php

namespace App\Cards;

class CardGraphic extends Card
{
    private string $symbol;

    /**
    * @var string[]
    */
    private static array $ranks = [
        'A' => 'A',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
        '9' => '9',
        '10' => '10',
        'J' => 'J',
        'Q' => 'Q',
        'K' => 'K'
    ];

    /**
    * @var string[]
    */
    private static array $suits = [
        'H' => "\u{2665}",
        'D' => "\u{2666}",
        'C' => "\u{2663}",
        'S' => "\u{2660}"
    ];

    public function __construct(string $rank, string $suit)
    {
        parent::__construct($rank, $suit);

        $this->symbol = self::$ranks[$rank] . self::$suits[$suit];
    }

    public function getSymbol(): string
    {
        return '['.$this->symbol.']';
    }

    public function setSymbol(string $symbol): void
    {
        $this->symbol = $symbol;
    }
}
