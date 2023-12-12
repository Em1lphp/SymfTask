<?php

namespace App\Controller;

use App\Services\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookBaseController extends AbstractController
{
    public $service;


    public function __construct(BookService $service)
    {
        $this->service = $service;
    }
}