<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGraphic.
 */
class ProductTest extends TestCase
{
    /**
     * Construct object with arguments.
     */
    public function testCreateObject(): void
    {
        $product = new Product();
        $this->assertInstanceOf("App\Entity\Product", $product);
    }

    /**
     * Test if setting and getting a Name works
     */
    public function testSetGetName(): void
    {
        $product = new Product();
        $product->setName("Produkten");
        $this->assertEquals($product->getName(), "Produkten");
    }

    /**
     * Test if setting and getting a Value works
     */
    public function testSetGetValue(): void
    {
        $product = new Product();
        $product->setValue(123);
        $this->assertEquals($product->getValue(), 123);
    }

    /**
     * Test if getting a ID works
     */
    public function testGetID(): void
    {
        $product = new Product();
        $this->assertEquals($product->getID(), 0);
    }
}
