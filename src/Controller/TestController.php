<?php

namespace App\Controller;

use App\Repository\AbsenceRepository;
use App\Repository\EmployerRepository;
use App\Repository\LeaveRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard', methods: ['GET'])]
    public function dashboard(EmployerRepository $employerRepository, UtilisateurRepository $utilisateurRepository, AbsenceRepository $absenceRepository, LeaveRepository $leaveRepository): Response
    {
        // Fetch the number of employers
        $employerCount = $employerRepository->count([]);
        $accountCount = $utilisateurRepository->count([]);
        $leavesRequestedToday = $leaveRepository->countLeavesRequestedToday();
        $absencesToday = $absenceRepository->findAbsencesToday();
        $data = [
            'non justifier' => 0,
            'congé' => 0,
            'autre' => 0,
        ];
        foreach ($absencesToday as $absence) {
            if ($absence->getStatus() == 'non justifier') {
                $data['non justifier']++;
            } elseif ($absence->getStatus() == 'congé') {
                $data['congé']++;
            } else {
                $data['autre']++;
            }
        }

        return $this->render('base.html.twig', [
            'employerCount' => $employerCount,
            'accountCount' => $accountCount,
            'leavesRequestedToday' => $leavesRequestedToday,
            'absences' => $absencesToday,
            'data' => $data,
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
