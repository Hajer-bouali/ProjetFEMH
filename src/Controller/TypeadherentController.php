<?php

namespace App\Controller;

use App\Entity\Typeadherent;
use App\Form\TypeadherentType;
use App\Repository\TypeadherentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/typeadherent")
 * @Security("is_granted('ROLE_SOCIALE') or is_granted('ROLE_ADMIN')")
 */
class TypeadherentController extends AbstractController
{
    #[Route('/', name: 'app_typeadherent_index', methods: ['GET'])]
    public function index(TypeadherentRepository $typeadherentRepository): Response
    {
        return $this->render('typeadherent/index.html.twig', [
            'typeadherents' => $typeadherentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_typeadherent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TypeadherentRepository $typeadherentRepository): Response
    {
        $typeadherent = new Typeadherent();
        $form = $this->createForm(TypeadherentType::class, $typeadherent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeadherentRepository->add($typeadherent);
            return $this->redirectToRoute('app_typeadherent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('typeadherent/new.html.twig', [
            'typeadherent' => $typeadherent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_typeadherent_show', methods: ['GET'])]
    public function show(Typeadherent $typeadherent): Response
    {
        return $this->render('typeadherent/show.html.twig', [
            'typeadherent' => $typeadherent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_typeadherent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Typeadherent $typeadherent, TypeadherentRepository $typeadherentRepository): Response
    {
        $form = $this->createForm(TypeadherentType::class, $typeadherent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeadherentRepository->add($typeadherent);
            return $this->redirectToRoute('app_typeadherent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('typeadherent/edit.html.twig', [
            'typeadherent' => $typeadherent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_typeadherent_delete', methods: ['POST'])]
    public function delete(Request $request, Typeadherent $typeadherent, TypeadherentRepository $typeadherentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeadherent->getId(), $request->request->get('_token'))) {
            $typeadherentRepository->remove($typeadherent);
        }

        return $this->redirectToRoute('app_typeadherent_index', [], Response::HTTP_SEE_OTHER);
    }
}
