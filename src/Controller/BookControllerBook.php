<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class BookControllerBook extends BookBaseController
{
    #[Route(path: '/book', name: 'book_index', methods: ['GET'])]
    public function index(BookRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $data = $this->service->index($repository, $serializer);
        return $this->json($data, Response::HTTP_OK, ['json_encode_options' => JSON_THROW_ON_ERROR]);
    }

    #[Route(path: '/book/by-author/{surname}', name: 'books_by_author', methods: ['GET'])]
    public function findByAuthor(
        BookRepository $repository,
        SerializerInterface $serializer,
        string $surname
    ): JsonResponse {
        $data = $this->service->findByAuthor($repository, $serializer, $surname);

        return $this->json($data, Response::HTTP_OK, ['json_encode_options' => JSON_THROW_ON_ERROR]);
    }


    #[Route(path: '/book/{id}', name: 'book_show', methods: ['GET'])]
    public function show(BookRepository $repository, SerializerInterface $serializer, int $id): JsonResponse
    {
        $data = $this->service->show($repository, $serializer, $id);

        return $this->json($data, Response::HTTP_OK, ['json_encode_options' => JSON_THROW_ON_ERROR]);
    }


    #[Route(path: '/book/create', name: 'book_create', methods: ['POST'])]
    public function createBook(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        if ($data !== null) {
            $this->service->store($data, $entityManager);
            return $this->json($data, Response::HTTP_OK, ['json_encode_options' => JSON_THROW_ON_ERROR]);
        } else {
            return $this->json(['error' => 'Invalid JSON data'], Response::HTTP_BAD_REQUEST);
        }
    }
}
