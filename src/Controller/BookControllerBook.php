<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Services\BookService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class BookControllerBook extends BookBaseController
{
    #[Route(path: '/book', name: 'app_book', methods: 'GET')]
    public function index(BookRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $books = $repository->findAll();

        $bookData = $serializer->normalize($books, null, ['groups' => 'book']);

        return new JsonResponse($bookData, Response::HTTP_OK);
    }



//    public function index(BookRepository $repository): JsonResponse
//    {
//        $books = $repository->findAll();
//        $bookData = [];
//        foreach ($books as $book) {
//            $bookData[] = [
//                'id' => $book->getId(),
//                'title' => $book->getTitle(),
//                'description' => $book->getDescription(),
//                'public_date' => $book->getPublicDate()->format('Y-m-d')
//            ];
//        }
//        return new JsonResponse($bookData, Response::HTTP_OK);
//    }


    #[Route(path: '/book/{id}', name: 'book_show', methods: ['GET'])]
    public function show(BookRepository $repository, SerializerInterface $serializer, int $id): JsonResponse
    {
        $book = $repository->find($id);

        if (!$book) {
            return $this->json(['message' => 'No book found for id ' . $id], Response::HTTP_NOT_FOUND);
        }

        $data = $serializer->normalize($book, null, ['groups' => 'book']);

        return $this->json($data, Response::HTTP_OK, ['json_encode_options' => JSON_THROW_ON_ERROR]);
    }


    #[Route('/book/create', name: 'create_book', methods: ['POST'])]
    public function createBook(
        Request $request,
        BookService $bookService,
        EntityManagerInterface $entityManager
    ): Response {
        $data = json_decode($request->getContent(), true);

        if ($data !== null) {
            return $bookService->store($data, $entityManager);
        }
        return new Response('Invalid book data', Response::HTTP_BAD_REQUEST);
    }

    #[Route('/book/by-author/{surname}', name: 'books_by_author', methods: ['GET'])]
    public function findByAuthor(
        BookRepository $repository,
        SerializerInterface $serializer,
        string $surname
    ): JsonResponse {
        $books = $repository->findByAuthorSurname($surname);

        if (empty($books)) {
            return $this->json('No books found for author with surname ' . $surname, 404);
        }

        $data = $serializer->normalize(
            $books,
            null,
            ['attributes' => ['id', 'title', 'description', 'author_id', 'public_date']]
        );

        return $this->json($data, Response::HTTP_OK, ['json_encode_options' => JSON_THROW_ON_ERROR]);
    }
}
