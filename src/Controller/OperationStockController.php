<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\OperationStock;
use App\Entity\Stock;
use App\Form\EvenementSocialType;
use App\Form\OperationStockAideType;
use App\Form\OperationStockDonType;
use App\Form\StockType;
use App\Repository\OperationStockRepository;
use App\Repository\ProduitRepository;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\OperationStock as EntityOperationStock;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/operation/stock")
 * @Security("is_granted('ROLE_SOCIAL') or is_granted('ROLE_ADMIN')")
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
            $operationStock->setResponsable($this->getUser()->getName());
            $operationStock->setDate(new \DateTime('now'));
            $entityManager->persist($operationStock);
            $entityManager->flush();

            return $this->redirectToRoute('operation_stock_don_index', []);
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
        $evenement = new Evenement();
        $formAide = $this->createForm(OperationStockAideType::class, $operationStock);
        $formAide->handleRequest($request);
        $formEvenement = $this->createForm(EvenementSocialType::class, $evenement);
        $formEvenement->handleRequest($request);

        if ($formAide->isSubmitted() && $formAide->isValid()) {
            $operationStock->setTypeoperation('aide');
            $operationStock->setResponsable($this->getUser()->getName());
            $operationStock->setDate(new \DateTime('now'));
            $entityManager->persist($operationStock);
            $entityManager->flush();

            return $this->redirectToRoute('operation_stock_aide_index', []);
        }
        if ($formEvenement->isSubmitted() && $formEvenement->isValid()) {
            $evenement->setEtat('valide');
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('operation_stock_aide_new', []);
        }

        return $this->renderForm('operation_stock_aide/new.html.twig', [
            'operation_stock' => $operationStock,
            'formAide' => $formAide,
            'formEvenement' => $formEvenement,
        ]);
    }

    /**
     * @Route("/don/{id}", name="operation_stock_don_show", methods={"GET", "POST"})
     */
    public function showDon(Request $request, OperationStock $operationStock, StockRepository $stockRepository, EntityManagerInterface $entityManager): Response
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
            throw (new NotFoundHttpException('OperationStock introuvable'));
        }

        $stocks = $stockRepository->findByOperationStock($operationStock);
        $stock = new Stock();

        $formstock = $this->createForm(StockType::class, $stock);
        $formstock->handleRequest($request);
        if ($formstock->isSubmitted() && $formstock->isValid()) {
            $produit = $stock->getProduit();
            if ($produit->getQuantite() < $stock->getQuantite()) {
                $this->addFlash('warning', 'Désolé mais nous navons pas la quantité démandée en stock!');
                return $this->redirectToRoute('operation_stock_aide_show', ['id' => $operationStock->getId()]);

            }
            $stock->setOperationStock($operationStock);
            $produit->setQuantite($produit->getQuantite() - $stock->getQuantite());
            $entityManager->persist($produit);
            $entityManager->persist($stock);
            $entityManager->flush();
            return $this->redirectToRoute('operation_stock_aide_show', ['id' => $operationStock->getId()]);

        }

        return $this->render('operation_stock_aide/show.html.twig', [
            'operationStock' => $operationStock,
            'stocks' => $stocks,
            'resultat' => $resultat,
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

            return $this->redirectToRoute('operation_stock_aide_index', []);
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

            return $this->redirectToRoute('operation_stock_don_index', []);
        }

        return $this->renderForm('operation_stock_don/edit.html.twig', [
            'operation_stock' => $operationStock,
            'formDon' => $formDon,
        ]);
    }

    /**
     * @Route("/delete/{id}/don", name="operation_stock_don_delete")
     */
    public function deletedon(OperationStock $OperationStock, EntityManagerInterface $entityManager): Response
    {
        $entityManager = $entityManager = $this->getDoctrine()->getManager();
       //$idoperationstock = $stock->getOperationstock()->getId();
        $entityManager->remove($OperationStock);
        $entityManager->flush();
        return $this->redirectToRoute('operation_stock_don_index', []);
        // return $this->redirectToRoute('operation_stock_don_show', ['id' => $idoperationstock]);
    }

    /**
     * @Route("/delete/{id}/aide", name="operation_stock_aide_delete")
     */
    public function deleteaide(OperationStock $operationStock, EntityManagerInterface $entityManager, ProduitRepository $ProduitRepository): Response
    {
        
        $entityManager = $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($operationStock);
       // $entityManager->$ProduitRepository->updateQuantiteProduit();
        $entityManager->flush();

        return $this->redirectToRoute('operation_stock_aide_index', []);
    }

    protected function calculeStockPossible(OperationStock $operationStock)
    {
        $stocks = $operationStock->getStock();

    }
}
