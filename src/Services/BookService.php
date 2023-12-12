<?php

namespace App\Services;


use App\Entity\Author;
use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class BookService
{
    public function index(BookRepository $repository, SerializerInterface $serializer): array // Возвращаем массив данных
    {
        $books = $repository->findAll();
        return $books;
    }







    public function store(array $data, EntityManagerInterface $entityManager)
    {
        $book = new Book();
        $book->setTitle($data['title']);
        $book->setDescription($data['description']);
        $book->setPublicDate(new \DateTime($data['public_date']));

        $author = $entityManager->getRepository(Author::class)->find($data['author_id']);

        if ($author) {
            $book->setAuthor($author);
            $entityManager->persist($book);
            $entityManager->flush();
            return new Response('Created book ', Response::HTTP_CREATED);
        } else {
//            throw new \InvalidArgumentException('Author not found');
            return new Response('Invalid book data', Response::HTTP_BAD_REQUEST);
        }

    }



}
