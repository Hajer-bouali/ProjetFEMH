<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Form\StockType;
use App\Repository\OperationStockRepository;
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
    public function edit(Request $request, Stock $stock,EntityManagerInterface $entityManager, OperationStockRepository $OperationStockRepository): Response
    {
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);
        $operations = $OperationStockRepository->findByStock($stock);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($operations as $operation) {
                if ($operation->getTypeoperation() === 'don' && $operation->getEtat() === 'valide') {
                    $stockquantite = $stock->getProduit()->getQuantite();
                    $quantiteoperation = $stock->getQuantite();
                    $stock->getProduit()->setQuantite($quantiteoperation + $stockquantite);
                }
                if ($operation->getTypeoperation() === 'aide' && $operation->getEtat() === 'valide') {
                    $stockquantite = $stock->getProduit()->getQuantite();
                    $quantiteoperation = $stock->getQuantite();
                    $stock->setQuantite($stockquantite - $quantiteoperation);
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('stock_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('stock/edit.html.twig', [
            'stock' => $stock,
            'operations'=>$operations,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="stock_delete")
     */
    public function delete(Stock $stock, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($stock);
        $entityManager->flush();

        return $this->redirectToRoute('stock_index', [], Response::HTTP_SEE_OTHER);
    }
}
