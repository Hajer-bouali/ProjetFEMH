<?php

namespace App\Controller;

use App\Entity\OperationFinanciere;
use App\Entity\PieceJointeOperation;
use App\Form\OperationFinanciereDonType;
use App\Form\OperationFinanciereAideType;
use App\Repository\OperationFinanciereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/operation/financiere")
 */
class OperationFinanciereController extends AbstractController
{
    /**
     * @Route("/don", name="operation_financiere_don_index", methods={"GET"})
     */
    public function indexdon(OperationFinanciereRepository $operationFinanciereRepository): Response
    {
        return $this->render('operation_financiere_don/index.html.twig', [
            'operation_financieres' => $operationFinanciereRepository->findAll('don'),
        ]);
    }
        /**
     * @Route("/aide", name="operation_financiere_aide_index", methods={"GET"})
     */
    public function indexaide(OperationFinanciereRepository $operationFinanciereRepository): Response
    {
        return $this->render('operation_financiere_aide/index.html.twig', [
            'operation_financieres' => $operationFinanciereRepository->findByTypeoperation('aide'),
        ]);
    }

    /**
     * @Route("/newDon", name="operation_financiere_don_new", methods={"GET", "POST"})
     */
    public function newDon(Request $request, EntityManagerInterface $entityManager): Response
    {
        $operationFinanciere = new OperationFinanciere();
        $formDon = $this->createForm(OperationFinanciereDonType::class, $operationFinanciere);

        if (!$this->isGranted('ROLE_FINANCIERE')) {
            $formDon->remove('etat');
        }
        $formDon->handleRequest($request);
        if ($formDon->isSubmitted() && $formDon->isValid()) {
            $piecejointes = $formDon->get('pieceJointeOperations')->getData();
            foreach($piecejointes as $piecejointe){
                $fichier=md5(uniqid()). '.' . $piecejointe->guessExtension();
                $piecejointe->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
                $image= new PieceJointeOperation();
                $image->setNom($fichier);
                $operationFinanciere->addPieceJointeOperation($image);
            }
            $operationFinanciere->setTypeoperation('don');
            $operationFinanciere->setEtat('Demande');
            $operationFinanciere->setResponsable($this->getUser()->getName());
            $entityManager->persist($operationFinanciere);
            $entityManager->flush();

            return $this->redirectToRoute('operation_financiere_don_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('operation_financiere_don/new.html.twig', [
            'operation_financiere' => $operationFinanciere,
            'formDon' => $formDon,
        ]);
    }
 /**
     * @Route("/newAide", name="operation_financiere_aide_new", methods={"GET", "POST"})
     */
    public function newAide(Request $request, EntityManagerInterface $entityManager): Response
    {
        $operationFinanciere = new OperationFinanciere();
        $formAide = $this->createForm(OperationFinanciereAideType::class, $operationFinanciere);

        if (!$this->isGranted('ROLE_FINANCIERE')) {
            $formAide->remove('etat');
        }
        $formAide->handleRequest($request);
        if ($formAide->isSubmitted() && $formAide->isValid()) {
            $piecejointes = $formAide->get('pieceJointeOperations')->getData();
            foreach($piecejointes as $piecejointe){
                $fichier=md5(uniqid()). '.' . $piecejointe->guessExtension();
                $piecejointe->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
                $image= new PieceJointeOperation();
                $image->setNom($fichier);
                $operationFinanciere->addPieceJointeOperation($image);
            }
            $operationFinanciere->setTypeoperation('aide');
            $operationFinanciere->setEtat('Demande');
            $operationFinanciere->setResponsable($this->getUser()->getName());
            $entityManager->persist($operationFinanciere);
            $entityManager->flush();

            return $this->redirectToRoute('operation_financiere_aide_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->renderForm('operation_financiere_aide/new.html.twig', [
            'operation_financiere' => $operationFinanciere,
            'formAide'=>$formAide,
        ]);
    }
    /**
     * @Route("don/{id}", name="operation_financiere_don_show", methods={"GET"})
     */
    public function showDon(OperationFinanciere $operationFinanciere): Response
    {
        return $this->render('operation_financiere_don/show.html.twig', [
            'operation_financiere' => $operationFinanciere,
        ]);
    }
    /**
     * @Route("aide/{id}", name="operation_financiere_aide_show", methods={"GET"})
     */
    public function showAide(OperationFinanciere $operationFinanciere): Response
    {
        return $this->render('operation_financiere_aide/show.html.twig', [
            'operation_financiere' => $operationFinanciere,
        ]);
    }
    /**
     * @Route("/{id}/editAide", name="operation_financiere_aide_edit", methods={"GET", "POST"})
     */
    public function editAide(Request $request, OperationFinanciere $operationFinanciere, EntityManagerInterface $entityManager): Response
    {
        $formAide = $this->createForm(OperationFinanciereAideType::class, $operationFinanciere);
        if (!$this->isGranted('ROLE_FINANCIERE')) {
            $formAide->remove('etat');
        }
        $formAide->handleRequest($request);

        if ($formAide->isSubmitted() && $formAide->isValid()) {
            $piecejointes = $formAide->get('pieceJointeOperations')->getData();
            foreach($piecejointes as $piecejointe){
                $fichier=md5(uniqid()). '.' . $piecejointe->guessExtension();
                $piecejointe->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
                $image= new PieceJointeOperation();
                $image->setNom($fichier);
                $operationFinanciere->addPieceJointeOperation($image);
            }
            $entityManager->flush();

            return $this->redirectToRoute('operation_financiere_aide_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('operation_financiere_aide/edit.html.twig', [
            'operation_financiere' => $operationFinanciere,
            'formAide' => $formAide,
        ]);
    }
     /**
     * @Route("/{id}/editDon", name="operation_financiere_don_edit", methods={"GET", "POST"})
     */
    public function editDon(Request $request, OperationFinanciere $operationFinanciere, EntityManagerInterface $entityManager): Response
    {
        $formDon = $this->createForm(OperationFinanciereDonType::class, $operationFinanciere);
        
        if (!$this->isGranted('ROLE_FINANCIERE')) {
            $formDon->remove('etat');
        }$formDon->handleRequest($request);

        if ($formDon->isSubmitted() && $formDon->isValid()) {
            $piecejointes = $formDon->get('pieceJointeOperations')->getData();
            foreach($piecejointes as $piecejointe){
                $fichier=md5(uniqid()). '.' . $piecejointe->guessExtension();
                $piecejointe->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
                $image= new PieceJointeOperation();
                $image->setNom($fichier);
                $operationFinanciere->addPieceJointeOperation($image);
            }
            $entityManager->flush();

            return $this->redirectToRoute('operation_financiere_don_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('operation_financiere_don/edit.html.twig', [
            'operation_financiere' => $operationFinanciere,
            'formDon' => $formDon,
        ]);
    }

    /**
     * @Route("aide/delete/{id}", name="operation_financiere_aide_delete")
     */
    public function deleteAide(Request $request, OperationFinanciere $operationFinanciere, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($operationFinanciere);
            $entityManager->flush();
 

        return $this->redirectToRoute('operation_financiere_aide_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("don/delete/{id}", name="operation_financiere_don_delete")
     */
    public function deleteDon(Request $request, OperationFinanciere $operationFinanciere, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($operationFinanciere);
            $entityManager->flush();
 

        return $this->redirectToRoute('operation_financiere_don_index', [], Response::HTTP_SEE_OTHER);
    }
}
