<?php

namespace App\Services;


use App\Entity\Author;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AuthorService
{
    public function store(array $data, EntityManagerInterface $entityManager)
    {
        $author = new Author();
        $author->setName($data['name']);
        $author->setSurname($data['surname']);

        $entityManager->persist($author);
        $entityManager->flush();
    }


}
