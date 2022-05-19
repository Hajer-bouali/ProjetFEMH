<?php

namespace App\Controller;

use App\Entity\Caisse;
use App\Entity\OperationFinanciere;
use App\Entity\PieceJointeOperation;
use App\Form\OperationFinanciereAideType;
use App\Form\OperationFinanciereDonType;
use App\Repository\OperationFinanciereRepository;
use App\Repository\HistoriqueRepository;
use App\Services\ServiceHistorique;
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
    public function indexaide(OperationFinanciereRepository $operationFinanciereRepository, HistoriqueRepository $historiquerepository): Response
    {
        $historiqueaides= $historiquerepository-> findByTypeOperation('aide');
        return $this->render('operation_financiere_aide/index.html.twig', [
            'historiqueaides'=>$historiqueaides,
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
            foreach ($piecejointes as $piecejointe) {
                $fichier = md5(uniqid()) . '.' . $piecejointe->guessExtension();
                $piecejointe->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
                $image = new PieceJointeOperation();
                $image->setNom($fichier);
                $operationFinanciere->addPieceJointeOperation($image);
            }
            $operationFinanciere->setTypeoperation('don');
            $operationFinanciere->setEtat('Demande');
            $operationFinanciere->setResponsable($this->getUser()->getName());
            if ($operationFinanciere->getMontant()>500 && $operationFinanciere->getModepaiement()==='espece' ) {
                $this->addFlash('warning','Le mode de paiement espéce de lopération ne peut pas dépasser 500 Dt');
                return $this->redirectToRoute('operation_financiere_don_new', ['id' => $operationFinanciere->getId()]);
            }
            $entityManager->persist($operationFinanciere);
            $entityManager->flush();
            $entityManager->getRepository(Caisse::class)->updateMontant($operationFinanciere->getCaisse());

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
    public function newAide(Request $request, EntityManagerInterface $entityManager, ServiceHistorique $serviceHistorique ): Response
    {
        $operationFinanciere = new OperationFinanciere();
        $formAide = $this->createForm(OperationFinanciereAideType::class, $operationFinanciere);

        if (!$this->isGranted('ROLE_FINANCIERE')) {
            $formAide->remove('etat');
        }
        $formAide->handleRequest($request);
        if ($formAide->isSubmitted() && $formAide->isValid()) {
            $piecejointes = $formAide->get('pieceJointeOperations')->getData();
            foreach ($piecejointes as $piecejointe) {
                $fichier = md5(uniqid()) . '.' . $piecejointe->guessExtension();
                $piecejointe->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
                $image = new PieceJointeOperation();
                $image->setNom($fichier);
                $operationFinanciere->addPieceJointeOperation($image);
            }
            $operationFinanciere->setTypeoperation('aide');
            $operationFinanciere->setEtat('Demande');
            $operationFinanciere->setResponsable($this->getUser()->getName());
            $montantoperation = $operationFinanciere->getMontant();
            $montantcaisse = $operationFinanciere->getCaisse()->getMontant();
            if ($montantoperation > $montantcaisse) {
                $this->addFlash('warning', 'le montant est insfusent');
                return $this->redirectToRoute('operation_financiere_aide_new', ['id' => $operationFinanciere->getId()]);
            }
            $entityManager->persist($operationFinanciere);
            $entityManager->flush();
            $entityManager->getRepository(Caisse::class)->updateMontant($operationFinanciere->getCaisse());
            $serviceHistorique->saveModifications([
                'user' => $this->getUser(),
                'table' => 'operationFinanciere',
                'ancien' => [],
                'nouveau' => $operationFinanciere->toArray(),
                'typeoperation' => 'aide'
            ]);

            return $this->redirectToRoute('operation_financiere_aide_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('operation_financiere_aide/new.html.twig', [
            'operation_financiere' => $operationFinanciere,
            'historiqueaides' => $historiqueaides,
            'formAide' => $formAide,
        ]);
    }

    /**
     * @Route("don/{id}", name="operation_financiere_don_show", methods={"GET"})
     */
    public function showDon(OperationFinanciere $operationFinanciere): Response
    {
        return $this->render('operation_financiere_don/show.html.twig', [
            'operation_financiere_don' => $operationFinanciere,
        ]);
    }

    /**
     * @Route("aide/{id}", name="operation_financiere_aide_show", methods={"GET"})
     */
    public function showAide(OperationFinanciere $operationFinanciere): Response
    {
        return $this->render('operation_financiere_aide/show.html.twig', [
            'operation_financiere_aide' => $operationFinanciere,
        ]);
    }

    /**
     * @Route("/{id}/editAide", name="operation_financiere_aide_edit", methods={"GET", "POST"})
     */
    public function editAide(Request $request, OperationFinanciere $operationFinanciere, EntityManagerInterface $entityManager, ServiceHistorique $serviceHistorique): Response
    {
        $formAide = $this->createForm(OperationFinanciereAideType::class, $operationFinanciere);
        if (!$this->isGranted('ROLE_FINANCIERE')) {
            $formAide->remove('etat');
        }
        $ancien = $operationFinanciere->toArray();
        $formAide->handleRequest($request);

        if ($formAide->isSubmitted() && $formAide->isValid()) {
            $piecejointes = $formAide->get('pieceJointeOperations')->getData();
            foreach ($piecejointes as $piecejointe) {
                $fichier = md5(uniqid()) . '.' . $piecejointe->guessExtension();
                $piecejointe->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
                $image = new PieceJointeOperation();
                $image->setNom($fichier);
                $operationFinanciere->addPieceJointeOperation($image);
            }
            $entityManager->flush();
            $entityManager->getRepository(Caisse::class)->updateMontant($operationFinanciere->getCaisse());
            $serviceHistorique->saveModifications([
                'user' => $this->getUser(),
                'table' => 'operationFinanciere',
                'ancien' => $ancien,
                'nouveau' => $operationFinanciere->toArray()
            ]);

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
            foreach ($piecejointes as $piecejointe) {
                $fichier = md5(uniqid()) . '.' . $piecejointe->guessExtension();
                $piecejointe->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
                $image = new PieceJointeOperation();
                $image->setNom($fichier);
                $operationFinanciere->addPieceJointeOperation($image);
            }
            if ($operationFinanciere->getMontant()>500 && $operationFinanciere->getModepaiement()==='espece' ) {
                $this->addFlash('warning','Le mode de paiement espéce de l\'opération ne peut pas dépasser 500 Dt');
                return $this->redirectToRoute('operation_financiere_don_edit', ['id' => $operationFinanciere->getId()]);
            }
            $entityManager->flush();
            $entityManager->getRepository(Caisse::class)->updateMontant($operationFinanciere->getCaisse());

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
        $caisse = $operationFinanciere->getCaisse();
        $entityManager->remove($operationFinanciere);
        $entityManager->flush();
        $entityManager->getRepository(Caisse::class)->updateMontant($caisse);

        return $this->redirectToRoute('operation_financiere_aide_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("don/delete/{id}", name="operation_financiere_don_delete")
     */
    public function deleteDon(Request $request, OperationFinanciere $operationFinanciere, EntityManagerInterface $entityManager): Response
    {
        $caisse = $operationFinanciere->getCaisse();
        $entityManager->remove($operationFinanciere);
        $entityManager->flush();
        $entityManager->getRepository(Caisse::class)->updateMontant($caisse);

        return $this->redirectToRoute('operation_financiere_don_index', [], Response::HTTP_SEE_OTHER);
    }
}
