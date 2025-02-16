<?php

namespace App\Controller;

use App\Entity\SocieteRecyclage;
use App\Form\SocieteRecyclageType;
use App\Repository\SocieteRecyclageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/societe-recyclage')]
class SocieteRecyclageController extends AbstractController
{
    #[Route('/', name: 'app_societe_recyclage_index', methods: ['GET'])]
    public function index(SocieteRecyclageRepository $repository): Response
    {
        return $this->render('societe_recyclage/index.html.twig', [
            'societes' => $repository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_societe_recyclage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $societe = new SocieteRecyclage();
        $form = $this->createForm(SocieteRecyclageType::class, $societe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($societe);
            $entityManager->flush();

            return $this->redirectToRoute('app_societe_recyclage_index');
        }

        return $this->render('societe_recyclage/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_societe_recyclage_show', methods: ['GET'])]
    public function show(SocieteRecyclage $societe): Response
    {
        return $this->render('societe_recyclage/show.html.twig', [
            'societe' => $societe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_societe_recyclage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SocieteRecyclage $societe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SocieteRecyclageType::class, $societe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_societe_recyclage_index');
        }

        return $this->render('societe_recyclage/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_societe_recyclage_delete', methods: ['POST'])]
    public function delete(Request $request, SocieteRecyclage $societe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$societe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($societe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_societe_recyclage_index');
    }
}
