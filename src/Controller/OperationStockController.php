<?php

namespace App\Controller;

use App\Entity\OperationStock;
use App\Entity\Produit;
use App\Entity\Stock;
use App\Form\OperationStockAideType;
use App\Form\OperationStockDonType;
use App\Form\StockType;
use App\Repository\OperationStockRepository;
use App\Repository\ProduitRepository;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/operation/stock")
 */
class OperationStockController extends AbstractController
{
    /**
     * @Route("/aide", name="operation_stock_aide_index", methods={"GET"})
     */
    public function indexAide(OperationStockRepository $operationStockRepository): Response
    {
        return $this->render('operation_stock_aide/index.html.twig', [
            'operation_stocks' => $operationStockRepository->findByTypeoperation('aide'),
        ]);
    }
    /**
     * @Route("/don", name="operation_stock_don_index", methods={"GET"})
     */
    public function indexDon(OperationStockRepository $operationStockRepository): Response
    {
        return $this->render('operation_stock_don/index.html.twig', [
            'operation_stocks' => $operationStockRepository->findByTypeoperation('don'),
        ]);
    }

    /**
     * @Route("/newdon", name="operation_stock_don_new", methods={"GET", "POST"})
     */
    public function newDon(Request $request, EntityManagerInterface $entityManager): Response
    {
        $operationStock = new OperationStock();
        $formdon = $this->createForm(OperationStockDonType::class, $operationStock);
        $formdon->handleRequest($request);

        if ($formdon->isSubmitted() && $formdon->isValid()) {
            $operationStock->setTypeoperation('don');
            $operationStock->setEtat('Demande');
            $operationStock->setResponsable($this->getUser()->getName());
            $operationStock->setDate(new \DateTime('now'));
            $entityManager->persist($operationStock);
            $entityManager->persist($operationStock);
            $entityManager->flush();

            return $this->redirectToRoute('operation_stock_don_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('operation_stock_don/new.html.twig', [
            'operation_stock' => $operationStock,
            'formDon' => $formdon,
        ]);
    }
    /**
     * @Route("/newaide", name="operation_stock_aide_new", methods={"GET", "POST"})
     */
    public function newAide(Request $request, EntityManagerInterface $entityManager): Response
    {
        $operationStock = new OperationStock();
        $formAide = $this->createForm(OperationStockAideType::class, $operationStock);
        $formAide->handleRequest($request);

        if ($formAide->isSubmitted() && $formAide->isValid()) {
            $operationStock->setTypeoperation('aide');
            $operationStock->setEtat('Demande');
            $operationStock->setResponsable($this->getUser()->getName());
            $operationStock->setDate(new \DateTime('now'));
            $entityManager->persist($operationStock);
            $entityManager->flush();

            return $this->redirectToRoute('operation_stock_aide_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('operation_stock_aide/new.html.twig', [
            'operation_stock' => $operationStock,
            'formAide' => $formAide,
        ]);
    }

    /**
     * @Route("/don/{id}", name="operation_stock_don_show", methods={"GET", "POST"})
     */
    public function showDon(Request $request,OperationStock $operationStock, StockRepository $stockRepository, EntityManagerInterface $entityManager): Response
    {
        $stocks = $stockRepository->findByOperationStock($operationStock);
        $stock = new Stock();

        $formstock = $this->createForm(StockType::class, $stock);
        $formstock->handleRequest($request);
        
        if ($formstock->isSubmitted() && $formstock->isValid()) {
            $produit = $stock->getProduit();
            $stock->setOperationStock($operationStock);
            $produit->setQuantite($produit->getQuantite() + $stock->getQuantite());
            $entityManager->persist($produit);
            $entityManager->persist($stock);
            $entityManager->flush();

            return $this->redirectToRoute('operation_stock_don_show', ['id' => $operationStock->getId()]);

        }
        return $this->render('operation_stock_don/show.html.twig', [
            'operationStock' => $operationStock,
            'stocks' => $stocks,
            'formstock' => $formstock->createView(),
        ]);
    }
    /**
     * @Route("/aide/{id}", name="operation_stock_aide_show", methods={"GET", "POST"})
     */
    public function showAide(Request $request, OperationStock $operationStock = null, StockRepository $stockRepository, ProduitRepository $produitRepository, EntityManagerInterface $entityManager): Response
    {
        $resultat = 0;

        if (!$operationStock) {
            throw(new NotFoundHttpException('OperationStock introuvable'));
        }

        $stocks = $stockRepository->findByOperationStock($operationStock);
        $stock = new Stock();

        $formstock = $this->createForm(StockType::class, $stock);
        $formstock->handleRequest($request);
        if ($formstock->isSubmitted() && $formstock->isValid()) {
            $produit = $stock->getProduit();
            if ($produit->getQuantite() < $stock->getQuantite()) {
                $this->addFlash('warning','Désolé mais nous navons pas la quantité démandée en stock!');
                return $this->redirectToRoute('operation_stock_aide_show', ['id' => $operationStock->getId()]);
            }
            $stock->setOperationStock($operationStock);
            $resultat = $resultat + ($produit->getQuantite() / $stock->getQuantite());
           
            //$produit->setQuantite($produit->getQuantite() - $stock->getQuantite());
            $entityManager->persist($produit);
            $entityManager->persist($stock);
            $entityManager->flush();
            return $this->redirectToRoute('operation_stock_aide_show', ['id' => $operationStock->getId()]);
        }
        
        return $this->render('operation_stock_aide/show.html.twig', [
            'operationStock' => $operationStock,
            'stocks' => $stocks,
            'resultat'=>$resultat,
            'formstock' => $formstock->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit/aide", name="operation_stock_aide_edit", methods={"GET", "POST"})
     */
    public function editAide(Request $request, OperationStock $operationStock, EntityManagerInterface $entityManager): Response
    {
        $formAide = $this->createForm(OperationStockAideType::class, $operationStock);
        $formAide->handleRequest($request);

        if ($formAide->isSubmitted() && $formAide->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('operation_stock_aide_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('operation_stock_aide/edit.html.twig', [
            'operation_stock' => $operationStock,
            'formAide' => $formAide,
        ]);
    }

    /**
     * @Route("/{id}/edit/don", name="operation_stock_don_edit", methods={"GET", "POST"})
     */
    public function editdon(Request $request, OperationStock $operationStock, EntityManagerInterface $entityManager): Response
    {
        $formDon = $this->createForm(OperationStockDonType::class, $operationStock);
        $formDon->handleRequest($request);

        if ($formDon->isSubmitted() && $formDon->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('operation_stock_don_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('operation_stock_don/edit.html.twig', [
            'operation_stock' => $operationStock,
            'formDon' => $formDon,
        ]);
    }

    /**
     * @Route("/delete/{id}/don", name="operation_stock_don_delete")
     */
    public function deletedon(Stock $stock, EntityManagerInterface $entityManager): Response
    {
        $entityManager = $entityManager = $this->getDoctrine()->getManager();
        $idoperationstock = $stock->getOperationstock()->getId();
        $entityManager->remove($stock);
        $entityManager->flush();
        return $this->redirectToRoute('operation_stock_don_show', ['id' => $idoperationstock]);
    }

    /**
     * @Route("/delete/{id}/aide", name="operation_stock_aide_delete")
     */
    public function deleteaide(OperationStock $operationStock, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($operationStock);
        $entityManager->flush();

        return $this->redirectToRoute('operation_stock_aide_index', [], Response::HTTP_SEE_OTHER);
    }

    protected function calculeStockPossible(OperationStock $operationStock) {
        $stocks = $operationStock->getStock();
        
    }
}
