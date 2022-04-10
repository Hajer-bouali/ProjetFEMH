<?php

namespace App\Controller;

use App\Entity\OperationFinanciereAide;
use App\Form\OperationFinanciereAideType;
use App\Repository\OperationFinanciereAideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/operation/financiere/aide")
 */
class OperationFinanciereAideController extends AbstractController
{
    /**
     * @Route("/", name="operation_financiere_aide_index", methods={"GET"})
     */
    public function index(OperationFinanciereAideRepository $operationFinanciereAideRepository): Response
    {
        return $this->render('operation_financiere_aide/index.html.twig', [
            'operation_financiere_aides' => $operationFinanciereAideRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="operation_financiere_aide_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $operationFinanciereAide = new OperationFinanciereAide();
        $form = $this->createForm(OperationFinanciereAideType::class, $operationFinanciereAide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $operationFinanciereAide->setEtat("Demande");
            $entityManager->persist($operationFinanciereAide);
            $entityManager->flush();

            return $this->redirectToRoute('operation_financiere_aide_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('operation_financiere_aide/new.html.twig', [
            'operation_financiere_aide' => $operationFinanciereAide,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="operation_financiere_aide_show", methods={"GET"})
     */
    public function show(OperationFinanciereAide $operationFinanciereAide): Response
    {
        return $this->render('operation_financiere_aide/show.html.twig', [
            'operation_financiere_aide' => $operationFinanciereAide,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="operation_financiere_aide_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, OperationFinanciereAide $operationFinanciereAide, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OperationFinanciereAideType::class, $operationFinanciereAide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('operation_financiere_aide_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('operation_financiere_aide/edit.html.twig', [
            'operation_financiere_aide' => $operationFinanciereAide,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="operation_financiere_aide_delete")
     */
    public function delete(Request $request, OperationFinanciereAide $operationFinanciereAide, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($operationFinanciereAide);
            $entityManager->flush();

        return $this->redirectToRoute('operation_financiere_aide_index', [], Response::HTTP_SEE_OTHER);
    }
}
