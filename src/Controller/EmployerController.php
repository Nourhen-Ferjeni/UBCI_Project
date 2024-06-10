<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class EmployerController extends AbstractController
{

    //------------------------------------Lister liste employer------------------------------------------------

    #[Route('/list/employer', name: 'app_employer', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('employer/affiche.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }
    //-----------------------------------Suppprimer employer-----------------------------------------------------------

    #[Route('/delete/{id}', name:"utilisateurDelete")]
    public function deleteUtilisateur (ManagerRegistry $doctrine , $id, UtilisateurRepository $utilisateurRepo) : Response {
        $em = $doctrine->getManager();
        $utilisateurDel= $utilisateurRepo->find($id);
        $em->remove($utilisateurDel);
        $em->flush();

        return $this->redirectToRoute("app_employer");
    }

    //-----------------------------------Récuperer by id---------------------------------------------------------------
    #[Route('/Employer/{id}', name: 'utilisateurDetails', methods: ['GET'])]
    public function DetailsEmp(int $id, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($id);
    
        return $this->render('employer/Detail_Emp.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }
    
    //-----------------------------------Ajouter utilisateur -----------------------------------------------------------

    public function hashPassword($plainPassword)
    {
        // Générez un sel aléatoire
        $salt = substr(str_replace('+', '.', base64_encode(random_bytes(16))), 0, 22);
        
        // Générer le hachage avec le sel généré
        $hashedPassword = crypt($plainPassword, '$2a$10$' . $salt);
        
        return $hashedPassword;
    }


    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

             $defaultImagePath = $this->getParameter('kernel.project_dir').'/public/upload/userimg.png';
             $uploadsDirectory = $this->getParameter('images_directorys');
             $newFilename = uniqid().'.'.pathinfo($defaultImagePath, PATHINFO_EXTENSION);
             copy($defaultImagePath, $uploadsDirectory.'/'.$newFilename);
            // Enregistrez le nom de fichier de l'image par défaut dans l'entité utilisateur
            $utilisateur->setImage($newFilename);

            $plainPassword =$utilisateur->getMdp() ;
            $hashedPassword = $this->hashPassword($plainPassword);
            $utilisateur->setMdp($hashedPassword);
            $entityManager->persist($utilisateur);
            $entityManager->flush();

           // return $this->redirectToRoute('listUtilisateur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('employer/AddCompteEmployer.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }


    


    
}
