<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use App\Form\BookType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


class ApiBookController extends AbstractController
{
    #[Route('/api/library/books', name: 'api_book_list')]
    public function getAllBooks(EntityManagerInterface $entityManager): JsonResponse
    {
        $bookRepository = $entityManager->getRepository(Book::class);
        $books = $bookRepository->findAll();

        $data = [];

        foreach ($books as $book) {
            $bookData = [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'ISBN' => $book->getISBN(),
                'author' => $book->getAuthor(),
                'picture' => $book->getPicture(),
            ];

            $data[] = $bookData;
        }

        $jsonData = json_encode($data, JSON_PRETTY_PRINT);

        return new JsonResponse($jsonData, JsonResponse::HTTP_OK, [], true);
    }

    #[Route('/api/library/book/{isbn}', name: 'api_book_by_isbn', methods: ['GET'])]
    public function getBookByISBN(EntityManagerInterface $entityManager, string $isbn): JsonResponse
    {
        $bookRepository = $entityManager->getRepository(Book::class);
        $book = $bookRepository->findOneBy(['ISBN' => $isbn]);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        $bookData = [
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'ISBN' => $book->getISBN(),
            'author' => $book->getAuthor(),
            'picture' => $book->getPicture(),
        ];

        $jsonData = json_encode($bookData, JSON_PRETTY_PRINT);

        return new JsonResponse($jsonData, JsonResponse::HTTP_OK, [], true);
    }
}
