<?php

namespace App\Controller;

use App\Entity\PiecesJointes;
use App\Form\PiecesJointes1Type;
use App\Repository\PiecesJointesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pieces/jointes')]
class PiecesJointesController extends AbstractController
{
    #[Route('/', name: 'pieces_jointes_index', methods: ['GET'])]
    public function index(PiecesJointesRepository $piecesJointesRepository): Response
    {
        return $this->render('pieces_jointes/index.html.twig', [
            'pieces_jointes' => $piecesJointesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'pieces_jointes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $piecesJointe = new PiecesJointes();
        $form = $this->createForm(PiecesJointes1Type::class, $piecesJointe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($piecesJointe);
            $entityManager->flush();

            return $this->redirectToRoute('pieces_jointes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pieces_jointes/new.html.twig', [
            'pieces_jointe' => $piecesJointe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'pieces_jointes_show', methods: ['GET'])]
    public function show(PiecesJointes $piecesJointe): Response
    {
        return $this->render('pieces_jointes/show.html.twig', [
            'pieces_jointe' => $piecesJointe,
        ]);
    }

    #[Route('/{id}/edit', name: 'pieces_jointes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PiecesJointes $piecesJointe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PiecesJointes1Type::class, $piecesJointe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('pieces_jointes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pieces_jointes/edit.html.twig', [
            'pieces_jointe' => $piecesJointe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'pieces_jointes_delete', methods: ['POST'])]
    public function delete(Request $request, PiecesJointes $piecesJointe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$piecesJointe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($piecesJointe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pieces_jointes_index', [], Response::HTTP_SEE_OTHER);
    }
}
