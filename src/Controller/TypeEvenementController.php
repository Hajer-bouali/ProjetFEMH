<?php

namespace App\Controller;

use App\Entity\TypeEvenement;
use App\Form\TypeEvenementType;
use App\Repository\TypeEvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/type/evenement")
 * @Security("is_granted('ROLE_SOCIALE') or is_granted('ROLE_ADMIN')")
 */
class TypeEvenementController extends AbstractController
{
    /**
     * @Route("/", name="type_evenement_index", methods={"GET"})
     */
    public function index(TypeEvenementRepository $typeEvenementRepository): Response
    {
        return $this->render('type_evenement/index.html.twig', [
            'type_evenements' => $typeEvenementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="type_evenement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeEvenement = new TypeEvenement();
        $form = $this->createForm(TypeEvenementType::class, $typeEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeEvenement);
            $entityManager->flush();

            return $this->redirectToRoute('type_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_evenement/new.html.twig', [
            'type_evenement' => $typeEvenement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="type_evenement_show", methods={"GET"})
     */
    public function show(TypeEvenement $typeEvenement): Response
    {
        return $this->render('type_evenement/show.html.twig', [
            'type_evenement' => $typeEvenement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_evenement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TypeEvenement $typeEvenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeEvenementType::class, $typeEvenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('type_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_evenement/edit.html.twig', [
            'type_evenement' => $typeEvenement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="type_evenement_delete", methods={"POST"})
     */
    public function delete(Request $request, TypeEvenement $typeEvenement, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($typeEvenement);
            $entityManager->flush();

        return $this->redirectToRoute('type_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
