<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Form\AgenceType;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AgenceController extends AbstractController
{
    #[Route('/agence/list', name: 'app_agence_list')]
    public function index(AgenceRepository $agenceRepository): Response
    {
        $agences = $agenceRepository->findAll();
        return $this->render('agence/list.html.twig', [
            'agences' => $agences,
        ]);
    }

    #[Route('/agence/add', name: 'app_agence_add')]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agence = $form->getData();
            $manager->persist($agence);
            $manager->flush();

            $this->addFlash('success', 'Agence ajoutée avec succès');
            return $this->redirectToRoute('app_agence_list');
        }

        return $this->render('agence/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
