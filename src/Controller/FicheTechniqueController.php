<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\FicheTechnique;
use App\Form\FicheTechniqueType;
use App\Repository\FicheTechniqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



/**
 * @Route("/fiche/technique")
 * @Security("is_granted('ROLE_SOCIAL') or is_granted('ROLE_ADMIN')")
 */
class FicheTechniqueController extends AbstractController
{
     /**
     * @Route("/", name="app_fiche_technique_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $ficheTechniques = $entityManager
            ->getRepository(FicheTechnique::class)
            ->findAll();

        return $this->render('fiche_technique/index.html.twig', [
            'fiche_techniques' => $ficheTechniques,
        ]);
    }

    /**
     * @Route("/new", name="app_fiche_technique_new", methods={"GET" , "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ficheTechnique = new FicheTechnique();
        $form = $this->createForm(FicheTechniqueType::class, $ficheTechnique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ficheTechnique);
            $entityManager->flush();

            return $this->redirectToRoute('app_fiche_technique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fiche_technique/new.html.twig', [
            'fiche_technique' => $ficheTechnique,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_fiche_technique_show", methods={"GET"})
     */
    public function show(FicheTechnique $ficheTechnique): Response
    {
        return $this->render('fiche_technique/show.html.twig', [
            'fiche_technique' => $ficheTechnique,
        ]);
    }

    /**
     * @Route("/{id}/edit/{evenement}", name="app_fiche_technique_edit", methods={"GET" , "POST"})
     */
    public function edit(Request $request, FicheTechnique $ficheTechnique,$evenement, EntityManagerInterface $entityManager): Response
    {
        $oldQuantite = $ficheTechnique->getQuantite();
        $form = $this->createForm(FicheTechniqueType::class, $ficheTechnique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $ficheTechnique->getProduit();
            $produit->setQuantite($produit->getQuantite() - (($ficheTechnique->getQuantite() - $oldQuantite) * $evenement->getNbpanierfinale()));
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('evenement_show', ['id' => $evenement], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('fiche_technique/edit.html.twig', [
            'fiche_technique' => $ficheTechnique,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}/{evenement}", name="app_fiche_technique_delete")
     */
    public function delete(FicheTechnique $ficheTechnique, $evenement, EntityManagerInterface $entityManager): Response
    {
        $produit = $ficheTechnique->getProduit();
        $produit->setQuantite($produit->getQuantite() + ($ficheTechnique->getQuantite() * $evenement->getNbpanierfinale()));
        $entityManager->persist($produit);
        $entityManager->remove($ficheTechnique);
        $entityManager->flush();

        return $this->redirectToRoute('evenement_show', ['id' => $evenement], Response::HTTP_SEE_OTHER);
    }
}
