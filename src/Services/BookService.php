<?php

namespace App\Services;


use App\Entity\Author;
use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class BookService
{
    public function index(
        BookRepository $repository,
        SerializerInterface $serializer
    ): array // Возвращаем массив данных
    {
        $books = $repository->findAll();
        $data = $serializer->normalize($books);

        return $data;
    }


    public function findByAuthor(BookRepository $repository, SerializerInterface $serializer, string $surname): array
    {
        $books = $repository->findByAuthorSurname($surname);

        if (empty($books)) {
            return ['message' => 'No books found for author with surname ' . $surname, 'status' => 404];
        }

        return $serializer->normalize($books);
    }


    public function show(BookRepository $repository, SerializerInterface $serializer, int $id): array
    {
        $book = $repository->find($id);
        if (!$book) {
            return ['message' => 'No book found for id ' . $id, 'status' => 404];
        }
        return $serializer->normalize($book);
    }


    public function store(array $data, EntityManagerInterface $entityManager)
    {
        $book = new Book();
        $book->setTitle($data['title']);
        $book->setDescription($data['description']);

        // Проверяем, является ли $data['author'] объектом Author или идентификатором
        if (isset($data['author'])) {
            $author = $data['author'];

            // Если $author не является объектом Author, предполагая, что это идентификатор
            if (!($author instanceof Author)) {
                // Находим объект Author по идентификатору
                $author = $entityManager->getRepository(Author::class)->find($data['author']);
            }

            // Если объект Author найден, устанавливаем его для книги
            if ($author instanceof Author) {
                $book->setAuthor($author);
            } else {
                // Обработка случая, когда автор не найден или не является объектом Author
                // Можно выбрать другое действие, например, создать нового автора или выдать ошибку
                // В данном примере просто устанавливаем NULL для автора книги
                $book->setAuthor(null);
            }
        }

        if (isset($data['public_date'])) {
            $publicDate = new \DateTime($data['public_date']);
            $book->setPublicDate($publicDate);
        }

        $entityManager->persist($book);
        $entityManager->flush();
    }

}