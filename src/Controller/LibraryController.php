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

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/add', name: 'add_book')]
    public function addBook(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('library');
        }

        return $this->render('library/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/library/book/{id}', name: 'book_details')]
    public function bookDetails(Book $book): Response
    {
        return $this->render('library/book_details.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/books', name: 'book_list')]
    public function bookList(EntityManagerInterface $entityManager): Response
    {
        $bookRepository = $entityManager->getRepository(Book::class);
        $books = $bookRepository->findAll();

        return $this->render('library/book_list.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/library/book/{id}/update', name: 'update_book')]
    public function showUpdateForm(Book $book): Response
    {
        $form = $this->createForm(BookType::class, $book);

        return $this->render('library/update.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    #[Route('/library/book/{id}/save', name: 'save_book', methods: ['POST'])]
    public function updateBook(Request $request, EntityManagerInterface $entityManager, Book $book): Response
    {
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('book_details', ['id' => $book->getId()]);
        }

        return $this->render('library/update.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }

    #[Route('/library/book/{id}/delete', name: 'delete_book', methods: ['POST'])]
    public function deleteBook(EntityManagerInterface $entityManager, Book $book): Response
    {
        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash('success', 'The book has been deleted.');

        return $this->redirectToRoute('book_list');
    }

    #[Route('/api/library/books', name: 'api_book_list')]
    public function getAllBooks(EntityManagerInterface $entityManager): Response
    {
        $repository = $entityManager->getRepository(Book::class);
        $books = $repository->findAll();

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

        $response = new JsonResponse($data);
        $response->setEncodingOptions(JSON_PRETTY_PRINT);

        return $response;
    }

    #[Route('/api/library/book/{isbn}', name: 'api_book_by_isbn', methods: ['GET'])]
    public function getBookByISBN(EntityManagerInterface $entityManager, string $isbn): JsonResponse
    {
        $repository = $entityManager->getRepository(Book::class);
        $book = $repository->findOneBy(['ISBN' => $isbn]);

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

        return new JsonResponse($bookData, JsonResponse::HTTP_OK);
    }
}
