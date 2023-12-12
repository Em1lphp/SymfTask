<?php

namespace App\Controller;


use App\Entity\Author;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class AuthorController extends AuthorBaseController
{
    #[Route( path:'/author', name: 'author_index', methods: ['GET'])]
    public function index(AuthorRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $authors = $repository->findAll();
        $data = $serializer->normalize($authors, null, ['attributes' => ['id', 'surname', 'name']]);
        return $this->json($data, Response::HTTP_OK, ['json_encode_options' => JSON_THROW_ON_ERROR]);
    }


//    public function index(ManagerRegistry $doctrine): JsonResponse
//    {
//        $authors = $doctrine->getRepository(Author::class)->findAll();
//        $data = [];
//
//        foreach ($authors as $author) {
//            $data[] = [
//                'id' => $author->getId(),
//                'surname' => $author->getSurname(),
//                'name' => $author->getName(),
//            ];
//        }
//        return $this->json($data);
//    }


    #[Route( path:'/author/create', name: 'create_author', methods: ['POST'])]
    public function createAuthor(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $this->service->store($data, $entityManager);

        return $this->json($data, Response::HTTP_OK, ['json_encode_options' => JSON_THROW_ON_ERROR]);
    }





}