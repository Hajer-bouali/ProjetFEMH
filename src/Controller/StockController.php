<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Form\StockType;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stock")
 */
class StockController extends AbstractController
{
    /**
     * @Route("/", name="stock_index", methods={"GET"})
     */
    public function index(StockRepository $stockRepository): Response
    {
        return $this->render('stock/index.html.twig', [
            'stocks' => $stockRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="stock_new", methods={"GET", "POST"})
     */
    function new (Request $request, EntityManagerInterface $entityManager): Response {
        $stock = new Stock();
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stock);
            $entityManager->flush();

            return $this->redirectToRoute('stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock/new.html.twig', [
            'stock' => $stock,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="stock_show", methods={"GET"})
     */
    public function show(Stock $stock): Response
    {
        return $this->render('stock/show.html.twig', [
            'stock' => $stock,
        ]);
    }

    /**
     * @Route("/{id}/edit/", name="stock_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Stock $stock, EntityManagerInterface $entityManager): Response
    {
        
        $quantiteStockNew = $stock->getQuantite();
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);
        $operation = $stock->getOperationStock();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($operation->getTypeoperation() === 'don') {
                $quantiteStockEdit = $stock->getQuantite();
                $produit = $stock->getProduit();
                $produit->setQuantite($produit->getQuantite() + ($quantiteStockEdit - $quantiteStockNew));
                $entityManager->persist($produit);
            }
    
            if ($operation->getTypeoperation() === 'aide') {
                $quantiteStockEdit = $stock->getQuantite();
                $produit = $stock->getProduit();
                $produit->setQuantite($produit->getQuantite() - ($quantiteStockEdit - $quantiteStockNew));
                $entityManager->persist($produit);
            }
    
            $entityManager->flush();
    
            if ($operation->getTypeoperation() === 'don') {
                return $this->redirectToRoute('operation_stock_don_show', ['id' => $operation->getId()], Response::HTTP_SEE_OTHER);
            }
            if ($operation->getTypeoperation() === 'aide') {
                return $this->redirectToRoute('operation_stock_aide_show', ['id' => $operation->getId()], Response::HTTP_SEE_OTHER);
            }
        }
       
        return $this->renderForm('stock/edit.html.twig', [
            'stock' => $stock,
            'operation' => $operation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="stock_delete", methods={"GET", "POST"})
     */
    public function delete(Stock $stock, EntityManagerInterface $entityManager): Response
    {
        $produit = $stock->getProduit();
        $quantite = $stock->getQuantite();
        $operationStock=$stock->getOperationStock()->getId();
        $typeOperation = $stock->getOperationStock()->getTypeoperation(); 
        $entityManager->remove($stock);
        if ($typeOperation === 'don') {
            $produit->setQuantite($produit->getQuantite() - $quantite);
            $entityManager->persist($produit);
            $entityManager->flush();
            return $this->redirectToRoute('operation_stock_don_show', ['id'=>$operationStock]);
        }
        if ($typeOperation === 'aide') {
            $produit->setQuantite($produit->getQuantite() + $quantite);
            $entityManager->persist($produit);
            $entityManager->flush();
            return $this->redirectToRoute('operation_stock_aide_show', ['id'=>$operationStock]);
        }
    }
}
