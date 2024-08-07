<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfilController extends AbstractController
{
    #[Route('/user/profil', name: 'app_user_profil')]
    public function index(): Response
    {
        return $this->render('user_profil/userProfil.html.twig', [
            'controller_name' => 'UserProfilController',
        ]);
    }
}
