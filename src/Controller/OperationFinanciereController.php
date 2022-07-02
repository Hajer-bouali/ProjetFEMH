<?php

namespace App\Controller;

use App\Entity\Caisse;
use App\Entity\OperationFinanciere;
use App\Entity\PieceJointeOperation;
use App\Form\OperationFinanciereAideType;
use App\Form\OperationFinanciereTypeDon;
use App\Repository\HistoriqueRepository;
use App\Repository\OperationFinanciereRepository;
use App\Services\ServiceHistorique;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Services\ServiceChiffreCaisse;
// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/operation/financiere")
 * @Security("is_granted('ROLE_FINANCIER') or is_granted('ROLE_ADMIN') or is_granted('ROLE_SOCIAL')")
 */
class OperationFinanciereController extends AbstractController
{   
    /**
     * @Route("/don", name="operation_financiere_don_index", methods={"GET"})
     */
    public function indexdon(OperationFinanciereRepository $operationFinanciereRepository, HistoriqueRepository $historiquerepository): Response
    {
        $historiquedons = $historiquerepository->findByTableModifiee('operationFinanciere');
        return $this->render('operation_financiere_don/index.html.twig', [
            'operation_financieres' => $operationFinanciereRepository->findAll('don'),
            'historiquedons' => $historiquedons,
        ]);
    }

    /**
     * @Route("/aide", name="operation_financiere_aide_index", methods={"GET"})
     */
    public function indexaide(OperationFinanciereRepository $operationFinanciereRepository, HistoriqueRepository $historiquerepository): Response
    {
        $operation_financieres = $operationFinanciereRepository->findByTypeoperation('aide');
        $historiqueaides = $historiquerepository->findByTableModifiee('operationFinanciere');
        return $this->render('operation_financiere_aide/index.html.twig', [
            'historiqueaides' => $historiqueaides,
            'operation_financieres' => $operation_financieres,
        ]);
    }

    /**
     * @Route("/newDon", name="operation_financiere_don_new", methods={"GET", "POST"})
     */
    public function newDon(Request $request, EntityManagerInterface $entityManager, ServiceHistorique $serviceHistorique): Response
    {
        $operationFinanciere = new OperationFinanciere();
        $formDon = $this->createForm(OperationFinanciereTypeDon::class, $operationFinanciere);

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
            $operationFinanciere->setDate(new \DateTime('now'));
            $operationFinanciere->setResponsable($this->getUser()->getName());
            if ($operationFinanciere->getMontant() > 500 && $operationFinanciere->getModepaiement() === 'espece') {
                $this->addFlash('warning', 'Le mode de paiement espéce de l opération ne peut pas dépasser 500 Dt');
                return $this->redirectToRoute('operation_financiere_don_new', ['id' => $operationFinanciere->getId()]);
            }
            $entityManager->persist($operationFinanciere);
            $entityManager->flush();

            $serviceHistorique->saveModifications([
                'user' => $this->getUser(),
                'table' => 'operationFinanciere',
                'ancien' => [],
                'nouveau' => $operationFinanciere->toArray(),
                'typeoperation' => 'ajout',
                'idligne' => $operationFinanciere->getId(),

            ]);
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
    public function newAide(Request $request, EntityManagerInterface $entityManager, ServiceHistorique $serviceHistorique): Response
    {
        $operationFinanciere = new OperationFinanciere();
        $formAide = $this->createForm(OperationFinanciereAideType::class, $operationFinanciere);

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
            $operationFinanciere->setDate(new \DateTime('now'));
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
                'typeoperation' => 'ajout',
                'idligne' => $operationFinanciere->getId(),

            ]);

            return $this->redirectToRoute('operation_financiere_aide_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('operation_financiere_aide/new.html.twig', [
            'operation_financiere' => $operationFinanciere,
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
                'nouveau' => $operationFinanciere->toArray(),
                'typeoperation' => 'edit',
                'idligne' => $operationFinanciere->getId(),
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
    public function editDon(Request $request, OperationFinanciere $operationFinanciere, EntityManagerInterface $entityManager, ServiceHistorique $serviceHistorique): Response
    {
        $formDon = $this->createForm(OperationFinanciereTypeDon::class, $operationFinanciere);
        $ancien = $operationFinanciere->toArray();
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
            if ($operationFinanciere->getMontant() > 500 && $operationFinanciere->getModepaiement() === 'espece') {
                $this->addFlash('warning', 'Le mode de paiement espéce de l\'opération ne peut pas dépasser 500 Dt');
                return $this->redirectToRoute('operation_financiere_don_edit', ['id' => $operationFinanciere->getId()]);
            }
            $entityManager->flush();
            $entityManager->getRepository(Caisse::class)->updateMontant($operationFinanciere->getCaisse());

            $serviceHistorique->saveModifications([

                'user' => $this->getUser(),
                'table' => 'operationFinanciere',
                'ancien' => $ancien,
                'nouveau' => $operationFinanciere->toArray(),
                'typeoperation' => 'edit',
                'idligne' => $operationFinanciere->getId(),
            ]);
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
    public function deleteAide( OperationFinanciere $operationFinanciere, EntityManagerInterface $entityManager): Response
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
    public function deleteDon(OperationFinanciere $operationFinanciere, EntityManagerInterface $entityManager): Response
    {
        $caisse = $operationFinanciere->getCaisse();
        $entityManager->remove($operationFinanciere);
        $entityManager->flush();
        $entityManager->getRepository(Caisse::class)->updateMontant($caisse);

        return $this->redirectToRoute('operation_financiere_don_index', [], Response::HTTP_SEE_OTHER);
    }
        /**
     * @Route("/{aide}/pdf", name="aide_pdf", methods={"GET"})
     */
    public function pdfaide(Request $request, OperationFinanciere $aide,EntityManagerInterface $entityManager)
    {
        
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        //$dompdf = new Dompdf(array('enable_remote' => true));

        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->set('IsFontSubsettingEnabled', true);
        $pdfOptions->set('IsHtml5ParserEnabled', true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->setOptions($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('operation_financiere_aide/pdf.html.twig', [
            'operationFinanciere' => $aide,
           
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
        
        // Render the HTML as PDF
        $dompdf->render();
        
        $dompdf->output(['isRemoteEnabled' => false]);
        // Output the generated PDF to Browser (force download)
        $dompdf->stream("un bon d ". $aide->getTypeoperation() . ".pdf", [
            "Attachment" => false
        ]);
        exit(0);
    }

}
