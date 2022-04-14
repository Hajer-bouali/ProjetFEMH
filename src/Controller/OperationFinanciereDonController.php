<?php

namespace App\Controller;

use App\Entity\OperationFinanciereDon;
use App\Form\OperationFinanciereDonType;
use App\Repository\OperationFinanciereDonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/operation/financiere/don")
 */
class OperationFinanciereDonController extends AbstractController
{
    /**
     * @Route("/", name="operation_financiere_don_index", methods={"GET"})
     */
    public function index(OperationFinanciereDonRepository $operationFinanciereDonRepository): Response
    {
        return $this->render('operation_financiere_don/index.html.twig', [
            'operation_financiere_dons' => $operationFinanciereDonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="operation_financiere_don_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $operationFinanciereDon = new OperationFinanciereDon();
        $form = $this->createForm(OperationFinanciereDonType::class, $operationFinanciereDon);
        if (!$this->isGranted('ROLE_FINANCIERE')) {
            $form->get('operation')->remove('etat');
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $operationFinanciereDon->getOperation()->setEtat('Demande');
            $operationFinanciereDon->getOperation()->setDate(new \DateTime('now'));
            $operationFinanciereDon->getOperation()->setResponsable($this->getUser()->getName());
            $entityManager->persist($operationFinanciereDon);
            $entityManager->flush();

            return $this->redirectToRoute('operation_financiere_don_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('operation_financiere_don/new.html.twig', [
            'operation_financiere_don' => $operationFinanciereDon,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="operation_financiere_don_show", methods={"GET"})
     */
    public function show(OperationFinanciereDon $operationFinanciereDon): Response
    {
        return $this->render('operation_financiere_don/show.html.twig', [
            'operation_financiere_don' => $operationFinanciereDon,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="operation_financiere_don_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, OperationFinanciereDon $operationFinanciereDon, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OperationFinanciereDonType::class, $operationFinanciereDon);
        if (!$this->isGranted('ROLE_FINANCIERE')) {
            $form->get('operation')->remove('etat');
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->flush();

            return $this->redirectToRoute('operation_financiere_don_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('operation_financiere_don/edit.html.twig', [
            'operation_financiere_don' => $operationFinanciereDon,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="operation_financiere_don_delete")
     */
    public function delete( OperationFinanciereDon $operationFinanciereDon, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($operationFinanciereDon);
            $entityManager->flush();
      

        return $this->redirectToRoute('operation_financiere_don_index', [], Response::HTTP_SEE_OTHER);
    }
}
