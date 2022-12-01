<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KernumericController extends AbstractController
{
    #[Route('/kernumeric', name: 'app_kernumeric')]
    public function index(): Response
    {
        return $this->render('kernumeric/index.html.twig', [
            'controller_name' => 'KernumericController',
        ]);
    }
}
