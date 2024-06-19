<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/test2', name: 'app_test2')]
    public function index2(): Response
    {
        return $this->render('employer/AddCompteEmployer.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/test3', name: 'app_test2')]
    public function index3(): Response
    {
        return $this->render('employer/congé.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    
}
