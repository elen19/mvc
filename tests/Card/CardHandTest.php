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

    /**
     * Test bet functions
     */
    public function testBet(): void {
        $guess = new CardHand();

        $res = $guess->getBet();
        $exp = 0;
        $this->assertEquals($exp, $res);

        $guess->setBet(10);
        $res = $guess->getBet();
        $exp = 10;
        $this->assertEquals($exp, $res);

        $guess = new CardHand();
        $guess->placeBet(10);
        $res = $guess->getBet();
        $exp = 10;
        $this->assertEquals($exp, $res);
        $res = $guess->getMoney();
        $exp = 90;
        $this->assertEquals($exp, $res);
        $res = $guess->placeBet(300);
        $exp = false;
        $this->assertEquals($exp, $res);
    }

    /**
     * Test money functions
     */
    public function testMoney(): void {
        $guess = new CardHand();

        $res = $guess->getMoney();
        $exp = 100;
        $this->assertEquals($exp, $res);

        $guess->setMoney(50);
        $res = $guess->getMoney();
        $exp = 50;
        $this->assertEquals($exp, $res);
    }

    /**
     * Test winnings function
     */
    public function testWinning(): void {
        $guess = new CardHand();

        $guess->placeBet(100);
        $guess->calculateWinnings(1.5);
        $res = $guess->getMoney();
        $exp = 150;
        $this->assertEquals($exp, $res);
    }

    /**
     * Test clear hand function
     */
    public function testClearHand(): void {
        $guess = new CardHand();
        $card = new CardGraphic("A", "C");
        $guess->addCard($card);
        $guess->stay();
        $guess->clearHand();
        $res = $guess->getCards();
        $exp = [];
        $this->assertEquals($exp, $res);
        $res = $guess->getStay();
        $exp = false;
        $this->assertEquals($exp, $res);
    }
}