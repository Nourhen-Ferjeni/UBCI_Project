<?php

namespace App\Controller;

use App\Entity\Employer;
use App\Entity\Leave;
use App\Form\LeaveType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployerGestionController extends AbstractController
{
    #[Route('/employer/Dashboard', name: 'app_employer_gestion')]
    public function index(): Response
    {
        return $this->render('baseEmployer.html.twig', [
            'controller_name' => 'EmployerGestionController',
        ]);
    }

    #[Route('/employer/profil', name: 'app_employer_profil')]
    public function profil(): Response
    {
        return $this->render('compteEmployer/profil.html.twig', [
            'controller_name' => 'EmployerGestionController',
        ]);
    }

    #[Route('/leave/new', name: 'app_leave_new')]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $leave = new Leave();

    // Définir par défaut l'employer avec l'ID 1
    $defaultEmployerId = 5;
    $defaultEmployer = $entityManager->getRepository(Employer::class)->find($defaultEmployerId);

    if (!$defaultEmployer) {
        throw $this->createNotFoundException('Employer not found');
    }

    $leave->setEmployer($defaultEmployer);

    $form = $this->createForm(LeaveType::class, $leave);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($leave);
        $entityManager->flush();

        $this->addFlash('success', 'Leave added successfully');

        return $this->redirectToRoute('app_employer'); // Assurez-vous que cette route existe et mène à la liste des leaves
    }

    return $this->render('compteEmployer/addCongé.html.twig', [
        'form' => $form->createView(),
    ]);
}


    // #[Route('/employer/addCongé', name: 'app_employer_addCongé')]
    // public function AddCongé(): Response
    // {
    //     return $this->render('compteEmployer/addCongé.html.twig', [
    //         'controller_name' => 'EmployerGestionController',
    //     ]);
    // }
}
