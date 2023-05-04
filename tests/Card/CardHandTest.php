<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardHand.
 */
class CardHandTest extends TestCase
{
    /**
     * Construct object without arguments.
     */
    public function testCreateObject(): void
    {
        $guess = new CardHand();
        $this->assertInstanceOf("App\Cards\CardHand", $guess);
    }

    /**
     * Test if a hand can get a card
     */
    public function testAddCard(): void {
        $guess = new CardHand();
        $card = new CardGraphic("5", "H");
        $guess->addCard($card);

        $res = $guess->getCards();
        $exp = $card;
        $this->assertEquals($exp, $res[0]);
    }

    /**
     * Test remove card
     */
    public function testRemoveCard(): void {
        $guess = new CardHand();
        $card = new CardGraphic("5", "H");
        $guess->addCard($card);

        $guess->removeCard($card);
        $res = $guess->getCards();
        $exp = [];
        $this->assertEquals($exp, $res);
    }

    /**
     * Test clear hand
     */
    public function testClear(): void {
        $guess = new CardHand();
        $card = new CardGraphic("5", "H");
        $guess->addCard($card);

        $guess->clear();
        $res = $guess->getCards();
        $exp = [];
        $this->assertEquals($exp, $res);
    }

    /**
     * Test blackJackHand
     */
    public function testBlackJack(): void {
        $guess = new CardHand();
        $card1 = new CardGraphic("5", "H");
        $card2 = new CardGraphic("A", "C");
        $guess->addCard($card1);
        $guess->addCard($card2);

        $res = $guess->blackJackHand();
        $exp = 16;
        $this->assertEquals($exp, $res);

        $card3 = new CardGraphic("A", "D");
        $guess->addCard($card3);
        $res = $guess->blackJackHand();
        $exp = 17;
        $this->assertEquals($exp, $res);
    }

    /**
     * Test stay functions
     */
    public function testStay(): void {
        $guess = new CardHand();

        $res = $guess->getStay();
        $exp = false;
        $this->assertEquals($exp, $res);

        $guess->stay();
        $res = $guess->getStay();
        $exp = true;
        $this->assertEquals($exp, $res);
    }
}