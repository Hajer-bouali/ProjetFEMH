<?php

namespace App\Controller;

use App\Entity\TypeProduit;
use App\Form\TypeProduitType;
use App\Repository\TypeProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/type/produit")
 * @Security("is_granted('ROLE_SOCIALE') or is_granted('ROLE_ADMIN')")
 */
class TypeProduitController extends AbstractController
{
    /**
     * @Route("/", name="type_produit_index", methods={"GET"})
     */
    public function index(TypeProduitRepository $typeProduitRepository): Response
    {
        return $this->render('type_produit/index.html.twig', [
            'type_produits' => $typeProduitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="type_produit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeProduit = new TypeProduit();
        $form = $this->createForm(TypeProduitType::class, $typeProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeProduit);
            $entityManager->flush();

            return $this->redirectToRoute('type_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_produit/new.html.twig', [
            'type_produit' => $typeProduit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="type_produit_show", methods={"GET"})
     */
    public function show(TypeProduit $typeProduit): Response
    {
        return $this->render('type_produit/show.html.twig', [
            'type_produit' => $typeProduit,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TypeProduit $typeProduit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeProduitType::class, $typeProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('type_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_produit/edit.html.twig', [
            'type_produit' => $typeProduit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="type_produit_delete")
     */
    public function delete( TypeProduit $typeProduit, EntityManagerInterface $entityManager): Response
    {
         $entityManager->remove($typeProduit);
         $entityManager->flush();
        return $this->redirectToRoute('type_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
