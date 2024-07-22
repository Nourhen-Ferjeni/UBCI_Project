<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Entity\Employer;
use App\Entity\Leave;
use App\Entity\Utilisateur;
use App\Form\AbsenceSearchType;
use App\Form\EmployeyType;
use App\Form\UtilisateurType;
use App\Repository\AbsenceRepository;
use App\Repository\EmployerRepository;
use App\Repository\LeaveRepository;
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

    //------------------------------------Lister liste compte employer------------------------------------------------

    #[Route('/list/compte', name: 'app_compte', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        
        return $this->render('employer/afficheCompte.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    //------------------------------------Lister liste absence employer------------------------------------------------

    #[Route('/list/absence', name: 'liste_absence', methods: ['GET'])]
    public function absence(Request $request, AbsenceRepository $absenceRepository): Response
    {
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');
        
        $query = $absenceRepository->createQueryBuilder('a')
            ->where('1=1');

        if ($startDate) {
            $query->andWhere('a.date >= :startDate')
                  ->setParameter('startDate', new \DateTime($startDate));
        }

        if ($endDate) {
            $query->andWhere('a.date <= :endDate')
                  ->setParameter('endDate', new \DateTime($endDate));
        }

        $absences = $query->getQuery()->getResult();

        return $this->render('employer/absence.html.twig', [
            'absences' => $absences,
        ]);
    
    }

    //------------------------------------Lister employer------------------------------------------------

    #[Route('/list/employer', name: 'app_employer', methods: ['GET'])]
    public function employer(EmployerRepository $employerRepository , UtilisateurRepository $utilisateurRepository): Response
    {
        $employers = $employerRepository->findAll();
        $employerAccounts = [];

        foreach ($employers as $employer) {
            $hasAccount = $utilisateurRepository->findOneBy(['employer' => $employer->getIdemployer()]) !== null;
            $employerAccounts[$employer->getIdemployer()] = $hasAccount;
        }

        return $this->render('employer/employerAffich.html.twig', [
            
            'employers' => $employers,
            'employerAccounts' => $employerAccounts
        ]);
    }


    //-----------------------------------Suppprimer compte employer-----------------------------------------------------------

    #[Route('/delete/{id}', name:"utilisateurDelete")]
    public function deleteUtilisateur (ManagerRegistry $doctrine , $id, UtilisateurRepository $utilisateurRepo) : Response {
        $em = $doctrine->getManager();
        $utilisateurDel= $utilisateurRepo->find($id);
        $em->remove($utilisateurDel);
        $em->flush();

        return $this->redirectToRoute("app_compte");
    }


    #[Route('/delete/employer/{id}', name:"employerDelete")]
    public function deleteEmployer (ManagerRegistry $doctrine , $id, EmployerRepository $employerRepo) : Response {
        $em = $doctrine->getManager();
        $employerDel= $employerRepo->find($id);
        $em->remove($employerDel);
        $em->flush();

        return $this->redirectToRoute("app_employer");
    }

    //-----------------------------------Récuperer by id---------------------------------------------------------------
    

    #[Route('/Employer/{id}', name: 'utilisateurDetails', methods: ['GET'])]
public function DetailsEmp(int $id, EntityManagerInterface $entityManager): Response
{
    $employer = $entityManager->getRepository(Employer::class)->find($id);
    $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['employer' => $employer]);

    $image = $utilisateur ? $utilisateur->getImage() : 'default-image.jpg';

    return $this->render('employer/Detail_Emp.html.twig', [
        'employer' => $employer,
        'utilisateur' => $utilisateur,
        'image' => $image,
    ]);
}
    
//---------------------------------------------------------------------------

#[Route('/leaves/liste', name: 'leave_list')]
public function listTout(EntityManagerInterface $entityManager): Response
{
    // Récupérer tous les enregistrements de la table Leave
    $leaves = $entityManager->getRepository(Leave::class)->findAll();

    return $this->render('employer/congé.html.twig', [
        'leaves' => $leaves,
    ]);
}



//----------------------------------------------------------------------------

#[Route('/leaves/liste/Mois', name: 'leave_list_mois')]
public function list(EntityManagerInterface $entityManager): Response
{
    $currentMonth = (new \DateTime())->format('m');
    $currentYear = (new \DateTime())->format('Y');
    
    // Créer une date de début et de fin pour le mois en cours
    $startOfMonth = new \DateTime("$currentYear-$currentMonth-01");
    $endOfMonth = clone $startOfMonth;
    $endOfMonth->modify('last day of this month');

    // Créer le QueryBuilder pour récupérer les enregistrements du mois en cours
    $queryBuilder = $entityManager->getRepository(Leave::class)->createQueryBuilder('l')
        ->where('l.dateajout >= :startOfMonth')
        ->andWhere('l.dateajout <= :endOfMonth')
        ->setParameter('startOfMonth', $startOfMonth)
        ->setParameter('endOfMonth', $endOfMonth);

    $leaves = $queryBuilder->getQuery()->getResult();

    return $this->render('employer/congé.html.twig', [
        'leaves' => $leaves,
    ]);
}



#[Route('/leave/delete/{id}', name: 'leave_delete')]
public function deleteLeave(ManagerRegistry $doctrine, $id, LeaveRepository $leaveRepo): Response
{
    $em = $doctrine->getManager();
    $leave = $leaveRepo->find($id);

    if ($leave) {
        $leave->setStatus('Refusé');
        $em->flush();
    }

    return $this->redirectToRoute("leave_list");
}


#[Route('/leave/accepter/{id}', name: 'leave_accepter')]
public function accepteLeave(ManagerRegistry $doctrine, $id, LeaveRepository $leaveRepo): Response
{
    $em = $doctrine->getManager();
    $leave = $leaveRepo->find($id);

    if ($leave) {
        $leave->setStatus('Accepté');
        $em->flush();
    }

    return $this->redirectToRoute("leave_list");
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
    // Récupération de l'employeur si nécessaire
    $id = $request->get('id'); // Vérifiez s'il est vraiment nécessaire de récupérer l'ID

    $employer = null;
    if ($id) {
        $employer = $entityManager->getRepository(Employer::class)->find($id);
        if (!$employer) {
            throw $this->createNotFoundException('Employer not found');
        }
    }

    $utilisateur = new Utilisateur();
    if ($employer) {
        $utilisateur->setEmployer($employer); // Associez l'employé à l'utilisateur
    }

    $form = $this->createForm(UtilisateurType::class, $utilisateur);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $defaultImagePath = $this->getParameter('kernel.project_dir').'/public/upload/userimg.png';
        $uploadsDirectory = $this->getParameter('images_directorys');
        $newFilename = uniqid().'.'.pathinfo($defaultImagePath, PATHINFO_EXTENSION);
        copy($defaultImagePath, $uploadsDirectory.'/'.$newFilename);
        // Enregistrez le nom de fichier de l'image par défaut dans l'entité utilisateur
        $utilisateur->setImage($newFilename);
        
        // Vérification du mot de passe et du mot de passe de confirmation
        $plainPassword = $form->get('mdp')->getData();
        $confirmPassword = $request->request->get('confirm_password');
        if ($plainPassword !== $confirmPassword) {
            $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
            return $this->redirectToRoute('app_utilisateur_new', ['id' => $id]);
        }

        $plainPassword = $utilisateur->getMdp();
        $hashedPassword = $this->hashPassword($plainPassword);
        $utilisateur->setMdp($hashedPassword);

        $entityManager->persist($utilisateur);
        $entityManager->flush();
        
        $this->addFlash('success', 'Le compte a été ajouté avec succès.');

        return $this->redirectToRoute('app_compte', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('employer/AddCompteEmployer.html.twig', [
        'utilisateur' => $utilisateur,
        'form' => $form,
    ]);
}

#[Route('/employer/create', name: 'employer_create')]
public function createEmployer(Request $request, EntityManagerInterface $entityManager): Response
{
    $employer = new Employer();
    $form = $this->createForm(EmployeyType::class, $employer);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrement de l'employé en base de données
        $entityManager->persist($employer);
        $entityManager->flush();

        $this->addFlash('success', 'Employé créé avec succès.');

        return $this->redirectToRoute('app_employer');
    }

    return $this->render('employer/createEmployer.html.twig', [
        'form' => $form->createView(),
    ]);
}



//---------------------------



 



    


    
}
