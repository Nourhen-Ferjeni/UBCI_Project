<?php

namespace App\Controller;

use App\Entity\Employer;
use App\Entity\Leave;
use App\Form\LeaveType;
use App\Repository\AbsenceRepository;
use App\Repository\EmployerRepository;
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

//----------------------------Absence ByEmployer------------------------------------------
#[Route('/absences/employer/{idemployer}', name: 'absences_by_employer')]
    public function absencesByEmployer(int $idemployer, AbsenceRepository $absenceRepository, EmployerRepository $employerRepository): Response
    {
        // Récupérer l'employé par son id
        $employer = $employerRepository->find($idemployer);

        if (!$employer) {
            throw $this->createNotFoundException('Employé non trouvé');
        }

        // Récupérer les absences de l'employé
        $absences = $absenceRepository->findBy(['employer' => $employer]);
        $data = [
            'non justifier' => 0,
            'congé' => 0,
            'autre' => 0,
        ];
        foreach ($absences as $absence) {
            if ($absence->getStatus() == 'non justifier') {
                $data['non justifier']++;
            } elseif ($absence->getStatus() == 'congé') {
                $data['congé']++;
            } else {
                $data['autre']++;
            }
        }

        return $this->render('employer/absences_by_employer.html.twig', [
            'employer' => $employer,
            'absences' => $absences,
            'data' => $data,
        ]);
    }
}
