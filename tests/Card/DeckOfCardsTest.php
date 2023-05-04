<?php

namespace App\Cards;
use Exception;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for DeckOfCards.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Construct object without arguments.
     */
    public function testCreateObject()
    {
        $guess = new DeckOfCards;
        $this->assertInstanceOf("App\Cards\DeckOfCards", $guess);
    }

    /**
     * Shuffle and sorted Test
     */
    public function testShuffleSort() {
        $sortedDeck = new DeckOfCards;
        $sortedDeck->sort();
        $shuffledDeck = new DeckOfCards;
        $shuffledDeck->shuffle();
        $this->assertNotEquals($sortedDeck, $shuffledDeck);

        $shuffledDeck->sort();
        $this->assertEquals($sortedDeck, $shuffledDeck);
    }

    /**
     * Draw a card test
     */
    public function testDraw() {
        $guess = new DeckOfCards;
        $guess->sort();

        $res = $guess->draw();
        $exp = new CardGraphic("A", "C");
        $this->assertEquals($exp, $res);

        for ($i=0; $i < 51; $i++) { 
            $guess->draw();
        }
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Deck is empty.');
        $guess->draw();
    }

    /**
     * Check that getNrOfCards works correct
     */
    public function testGetNrCards() {
        $guess = new DeckOfCards;

        $res = $guess->getNrOfCards();
        $exp = 52;
        $this->assertEquals($exp, $res);
    }

    /**
     * Test the function getCards
     */
    public function testGetCards() {
        $guess = new DeckOfCards;
        $guess->sort();

        for ($i=0; $i < 51; $i++) { 
            $guess->draw();
        }
        $res = $guess->getCards();
        $card = new CardGraphic("K", "S");
        $exp = [$card];
        $this->assertEquals($exp, $res);
    }
}