<?php

namespace App\Controller;

use App\Entity\Adherent;

use App\Entity\Revenufamilial;
use App\Form\RevenufamilialType;
use App\Repository\RevenufamilialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/revenufamilial')]
class RevenufamilialController extends AbstractController
{
    #[Route('/', name: 'revenufamilial_index', methods: ['GET'])]
    public function index(RevenufamilialRepository $revenufamilialRepository): Response
    {
        return $this->render('revenufamilial/index.html.twig', [
            'revenufamilials' => $revenufamilialRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'revenufamilial_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $revenufamilial = new Revenufamilial();
        $form = $this->createForm(RevenufamilialType::class, $revenufamilial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($revenufamilial);
            $entityManager->flush();

            return $this->redirectToRoute('revenufamilial_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('revenufamilial/new.html.twig', [
            'revenufamilial' => $revenufamilial,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'revenufamilial_show', methods: ['GET'])]
    public function show(Revenufamilial $revenufamilial): Response
    {
        return $this->render('revenufamilial/show.html.twig', [
            'revenufamilial' => $revenufamilial,
        ]);
    }

    #[Route('/{id}/edit', name: 'revenufamilial_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Revenufamilial $revenufamilial, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RevenufamilialType::class, $revenufamilial);
        $form->handleRequest($request);
        $idAdherent = $revenufamilial->getAdherent()->getId();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('adherent_show', ['id'=>$idAdherent]);
        }

        return $this->renderForm('revenufamilial/edit.html.twig', [
            'revenufamilial' => $revenufamilial,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}/{idadherent}', name: 'revenufamilial_delete')]
    public function delete(Request $request, Revenufamilial $revenufamilial, $idadherent, EntityManagerInterface $entityManager): Response
    {

        $entityManager->remove($revenufamilial);
        $entityManager->flush();
        return $this->redirectToRoute('adherent_show', ['id' => $idadherent], Response::HTTP_SEE_OTHER);
    }
}
