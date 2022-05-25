<?php

namespace App\Controller;

use App\Entity\TypeCaisse;
use App\Form\TypeCaisseType;
use App\Repository\TypeCaisseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/type/caisse")
 * @Security("is_granted('ROLE_FINANCIEUR') or is_granted('ROLE_ADMIN')")
 */
class TypeCaisseController extends AbstractController
{
    /**
     * @Route("/", name="type_caisse_index", methods={"GET"})
     */
    public function index(TypeCaisseRepository $typeCaisseRepository): Response
    {
        return $this->render('type_caisse/index.html.twig', [
            'type_caisses' => $typeCaisseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="type_caisse_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeCaisse = new TypeCaisse();
        $form = $this->createForm(TypeCaisseType::class, $typeCaisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeCaisse);
            $entityManager->flush();

            return $this->redirectToRoute('type_caisse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_caisse/new.html.twig', [
            'type_caisse' => $typeCaisse,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="type_caisse_show", methods={"GET"})
     */
    public function show(TypeCaisse $typeCaisse): Response
    {
        return $this->render('type_caisse/show.html.twig', [
            'type_caisse' => $typeCaisse,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_caisse_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TypeCaisse $typeCaisse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeCaisseType::class, $typeCaisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('type_caisse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_caisse/edit.html.twig', [
            'type_caisse' => $typeCaisse,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="type_caisse_delete", methods={"POST"})
     */
    public function delete(Request $request, TypeCaisse $typeCaisse, EntityManagerInterface $entityManager): Response
    {
            $entityManager->remove($typeCaisse);
            $entityManager->flush();
        

        return $this->redirectToRoute('type_caisse_index', [], Response::HTTP_SEE_OTHER);
    }
}
