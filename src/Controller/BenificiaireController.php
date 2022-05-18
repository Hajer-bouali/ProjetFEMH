<?php

namespace App\Controller;

use App\Entity\Benificiaire;
use App\Form\BenificiaireType;
use App\Repository\BenificiaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/benificiaire")
 */
class BenificiaireController extends AbstractController
{
    /**
     * @Route("/", name="app_benificiaire_index", methods={"GET"})
     */
    public function index(BenificiaireRepository $benificiaireRepository): Response
    {
        return $this->render('benificiaire/index.html.twig', [
            'benificiaires' => $benificiaireRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new", name="app_benificiaire_new", methods={"GET","POST"})
     */
    function new (Request $request, BenificiaireRepository $benificiaireRepository): Response {
        $benificiaire = new Benificiaire();
        $form = $this->createForm(BenificiaireType::class, $benificiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $benificiaireRepository->add($benificiaire);
            return $this->redirectToRoute('app_benificiaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('benificiaire/new.html.twig', [
            'benificiaire' => $benificiaire,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/{id}", name="app_benificiaire_show", methods={"GET"})
     */
    public function show(Benificiaire $benificiaire): Response
    {
        return $this->render('benificiaire/show.html.twig', [
            'benificiaire' => $benificiaire,
        ]);
    }
    /**
     * @Route("/{id}/edit", name="app_benificiaire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Benificiaire $benificiaire, BenificiaireRepository $benificiaireRepository): Response
    {
        $form = $this->createForm(BenificiaireType::class, $benificiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $benificiaireRepository->add($benificiaire);
            return $this->redirectToRoute('adherent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('benificiaire/edit.html.twig', [
            'benificiaire' => $benificiaire,
            'form' => $form,
        ]);
    }
    /**
     * @Route("/delete/{id}", name="app_benificiaire_delete")
     */
    public function delete(Request $request, Benificiaire $benificiaire, BenificiaireRepository $benificiaireRepository): Response
    {
            $benificiaireRepository->remove($benificiaire);

        return $this->redirectToRoute('app_benificiaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
