<?php

namespace App\Controller;

use App\Entity\Employer;
use App\Form\EmployerModType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        return $this->render('user_profil/userProfil.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }


    #[Route('/employer/edit/{id}', name: 'employer_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $employer = $em->getRepository(Employer::class)->find($id);

        if (!$employer) {
            throw $this->createNotFoundException('No employer found for id ' . $id);
        }

        $form = $this->createForm(EmployerModType::class, $employer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Profile updated successfully!');

            return $this->redirectToRoute('employer_edit', ['id' => $id]);
        }

        return $this->render('employer/edit.html.twig', [
            'form' => $form->createView(),
            'employer' => $employer
        ]);
    }
}
