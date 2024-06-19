<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\LoginType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'app_utilisateur')]
    public function index(): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'controller_name' => 'UtilisateurController',
        ]);
    }


//-------------------------Login-----------------------------------------

#[Route('/login', name : "login_user")]
public function login (UtilisateurRepository $userRepo ,Request $request , SessionInterface $session ): Response{
    $user = new Utilisateur();
    $form1 = $this->createForm(LoginType::class, $user);
    $form1->handleRequest($request);

    if ($form1->isSubmitted() && $form1->isValid()){
        $login = $user->getLogin();
        $mdp = $user->getMdp();
        $result = $userRepo->login($login, $mdp);
        if ($result instanceof Utilisateur) {

       $session ->set('user_id', $result->getId()) ;
       $session->set('user_image', $result->getImage()) ;
       $session->set('user_nom', $result->getApropos()) ;
       $session->set('user_prenom', $result->getRole()) ;
       $session->set('user_profil', $result->getDateajout()) ;
       $session->set('user_profil', $result->getEmployer()) ;
       $session->set('userConnected', $result) ;}   

        return $this->render('login.html.twig',[
            'user' => $result,
            'form1' => $form1->createView(),
        ]) ;
    } else {
            return $this->render('login.html.twig',[
                                'form1' => $form1->createView(),
                                'user'=> "no user found !!!",
        ]) ;
        }
}

}
