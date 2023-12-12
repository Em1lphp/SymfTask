<?php

namespace App\Controller;

use App\Services\AuthorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthorBaseController extends AbstractController
{
    public $service;


    public function __construct(AuthorService $service)
    {
        $this->service = $service;
    }
}