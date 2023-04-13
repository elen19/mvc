<?php

namespace App\Cards;

class CardGraphic extends Card
{
    private $symbol;

    public function __construct($rank, $suit)
    {
        parent::__construct($rank, $suit);

        switch ($rank) {
            case 'A':
                $this->symbol = 'A';
                break;
            case '2':
                $this->symbol = '2';
                break;
            case '3':
                $this->symbol = '3';
                break;
            case '4':
                $this->symbol = '4';
                break;
            case '5':
                $this->symbol = '5';
                break;
            case '6':
                $this->symbol = '6';
                break;
            case '7':
                $this->symbol = '7';
                break;
            case '8':
                $this->symbol = '8';
                break;
            case '9':
                $this->symbol = '9';
                break;
            case '10':
                $this->symbol = '10';
                break;
            case 'J':
                $this->symbol = 'J';
                break;
            case 'Q':
                $this->symbol = 'Q';
                break;
            case 'K':
                $this->symbol = 'K';
                break;
        }

        switch ($suit) {
            case 'H':
                $this->symbol .= "\u{2665}";
                break;
            case 'D':
                $this->symbol .= "\u{2666}";
                break;
            case 'C':
                $this->symbol .= "\u{2663}";
                break;
            case 'S':
                $this->symbol .= "\u{2660}";
                break;
        }
    }

    public function getSymbol()
    {
        return '['.$this->symbol.']';
    }

    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }
}
