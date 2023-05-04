<?php

namespace App\Cards;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGraphic.
 */
class CardGraphicTest extends TestCase
{
    /**
     * Construct object with arguments.
     */
    public function testCreateObject(): void
    {
        $guess = new CardGraphic("5","H");
        $this->assertInstanceOf("App\Cards\CardGraphic", $guess);

        $res = $guess->getSymbol();
        $exp = "[5\u{2665}]";
        $this->assertEquals($exp, $res);
    }

    /**
     * Construct an object and change the symbol
     */
    public function testSetObject(): void {
        $guess = new CardGraphic("5", "H");
        $guess->setSymbol("3C");

        $res = $guess->getSymbol();
        $exp = "[3C]";
        $this->assertEquals($exp, $res);
    }
}
