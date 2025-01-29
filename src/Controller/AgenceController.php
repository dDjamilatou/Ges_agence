<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Form\AgenceType;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AgenceController extends AbstractController
{
    #[Route('/agence/list', name: 'app_agence_list', methods: ['GET'])]
    public function list(AgenceRepository $agenceRepository): Response
    {
        $agences = $agenceRepository->findAll();
        return $this->render('agence/list.html.twig', [
            'agences' => $agences,
        ]);
    }

    #[Route('/agence/add', name: 'app_agence_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $manager): Response
    {
        $agence = new Agence();
        // Récupérer le dernier numéro d'agence pour générer un nouveau numéro
        $lastAgence = $manager->getRepository(Agence::class)->findOneBy([], ['id' => 'DESC']);
        
        if ($lastAgence) {
            // Récupérer le numéro actuel, retirer le préfixe "AG" et incrémenter le numéro
            $lastNumero = $lastAgence->getNumero();
            $lastNumber = (int) substr($lastNumero, 2); // Retirer le "AG"
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT); // Ajouter des zéros si nécessaire
            $agence->setNumero('AG' . $newNumber);
        } else {
            // Si aucune agence n'existe, commencer avec "AG001"
            $agence->setNumero('AG001');
        }
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
