<?php

namespace App\Repository;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }


    public function findByAuthorSurname(string $surname): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.author', 'a')
            ->andWhere('a.surname = :surname')
            ->setParameter('surname', $surname)
            ->getQuery()
            ->getResult();
    }

    public function create(
        string $title,
        string $description,
        int $authorId,
        ?string $authorName,
        \DateTimeInterface $publicDate
    ): Book {
        $author = $this->_em->getRepository(Author::class)->find($authorId);

        if (!$author) {
            // Обработайте ситуацию, когда автор не найден, например, создайте нового автора или верните ошибку
        }

        $book = new Book();
        $book
            ->setTitle($title)
            ->setDescription($description)
            ->setAuthor($author) // Передаем объект Author, а не идентификатор
            ->setPublicDate($publicDate);

        $this->_em->persist($book);
        $this->_em->flush();

        return $book;
    }
//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}