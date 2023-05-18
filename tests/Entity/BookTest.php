<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class CardGraphic.
 */
class BookTest extends TestCase
{
    /**
     * Construct object with arguments.
     */
    public function testCreateObject(): void
    {
        $book = new Book();
        $this->assertInstanceOf("App\Entity\Book", $book);
    }

    /**
     * Test if setting and getting a title works
     */
    public function testSetGetTitle(): void
    {
        $book = new Book();
        $book->setTitle("En bok");
        $this->assertEquals($book->getTitle(), "En bok");
    }

    /**
     * Test if setting and getting Author works
     */
    public function testSetGetAuthor(): void
    {
        $book = new Book();
        $book->setAuthor("Knut Knutsson");
        $this->assertEquals($book->getAuthor(), "Knut Knutsson");
    }

    /**
     * Test if getting ID works
     */
    public function testGetID(): void
    {
        $book = new Book();
        $this->assertEquals($book->getID(), 0);
    }

    /**
     * Test if setting and getting ISBN works
     */
    public function testSetGetISBN(): void
    {
        $book = new Book();
        $book->setISBN(1234);
        $this->assertEquals($book->getISBN(), 1234);
    }

    /**
     * Test if setting and getting Picture works
     */
    public function testSetGetPicture(): void
    {
        $book = new Book();
        $book->setPicture("pic.png");
        $this->assertEquals($book->getPicture(), "pic.png");
    }
}