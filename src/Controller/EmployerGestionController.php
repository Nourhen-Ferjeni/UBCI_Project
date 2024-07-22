<?php

namespace App\Controller;

use App\Entity\Absence;
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


    //-----------------------------------------Ajouter Congé------------------------------------------------------

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

//---------------------------Liste congé--------------------------------------------------

#[Route('/employer/absences', name: 'employer_absences')]
public function listAbsences(EntityManagerInterface $em): Response
{
    $id = 5; // ID de l'employé
    $employer = $em->getRepository(Employer::class)->find($id);

    if (!$employer) {
        throw $this->createNotFoundException('No employer found for id ' . $id);
    }

    $absences = $em->getRepository(Absence::class)->findBy(['employer' => $employer]);
     // Count the absences
     $absenceCount = $em->getRepository(Absence::class)->count(['employer' => $employer]);

    return $this->render('compteEmployer/listAbsence.html.twig', [
        'employer' => $employer,
        'absences' => $absences,
        'absenceCount' => $absenceCount,
        
    ]);
}


//------------------------Liste congé-----------------------------------------------

#[Route('/employer/listecongé', name: 'employer_congé')]
public function listCongés(EntityManagerInterface $em): Response
{
    $id = 5; // ID de l'employé
    $employer = $em->getRepository(Employer::class)->find($id);

    if (!$employer) {
        throw $this->createNotFoundException('No employer found for id ' . $id);
    }

    $absences = $em->getRepository(Absence::class)->findBy([
        'employer' => $employer,
        'status' => 'congé'
    ]);

    return $this->render('compteEmployer/ListeCongé.html.twig', [
        'employer' => $employer,
        'absences' => $absences,
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
