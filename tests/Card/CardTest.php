<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Card.
 */
class CardTest extends TestCase
{
    /**
     * Construct object with arguments.
     */
    public function testCreateObject()
    {
        $guess = new Card("5","H");
        $this->assertInstanceOf("App\Cards\Card", $guess);

        $res = $guess->getRank();
        $exp = "5";
        $this->assertEquals($exp, $res);

        $res = $guess->getSuit();
        $exp = "H";
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct an object and change the suit and rank
     */
    public function testSetObject() {
        $guess = new Card("5", "H");
        $guess->setRank("3");
        $guess->setSuit("C");

        $res = $guess->getRank();
        $exp = "3";
        $this->assertEquals($exp, $res);

        $res = $guess->getSuit();
        $exp = "C";
        $this->assertEquals($exp, $res);
    }
}
