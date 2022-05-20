<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\FicheTechnique;
use App\Form\EvenementType;
use App\Form\FicheTechniqueType;
use App\Repository\AdherentRepository;
use App\Repository\EvenementRepository;
use App\Repository\FicheTechniqueRepository;
use App\Repository\OperationFinanciereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/evenement")
 */
class EvenementController extends AbstractController
{
    /**
     * @Route("/", name="evenement_index", methods={"GET"})
     */
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="evenement_new", methods={"GET", "POST"})
     */
    function new (Request $request, EntityManagerInterface $entityManager): Response {
        $evenement = new Evenement();

        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="evenement_show", methods={"GET", "POST"})
     */
    public function show(Request $request, Evenement $evenement, AdherentRepository $adherentRepository, EntityManagerInterface $entityManager, OperationFinanciereRepository $OperationFinanciereRepository, FicheTechniqueRepository $fichetechniqueRepository): Response
    {
        $adherents = $adherentRepository->findAll();
        $ficheTechniques = $fichetechniqueRepository->findByEvenement($evenement);
        $ficheTechnique = new FicheTechnique();
        $nbstockproduit = 0;
        $nbpanierfinale = 10;

        $formfichetechnique = $this->createForm(FicheTechniqueType::class, $ficheTechnique);
        $formfichetechnique->handleRequest($request);

        if ($formfichetechnique->isSubmitted() && $formfichetechnique->isValid()) {
            $produit = $ficheTechnique->getProduit();
            if ($produit->getQuantite() < $ficheTechnique->getQuantite()) {
                $this->addFlash('warning', 'Désolé mais nous navons pas la quantité démandée en stock!');
                return $this->redirectToRoute('evenement_show', ['id' => $evenement->getId()]);
            }

            $nbstockproduit = ($produit->getQuantite() / $ficheTechnique->getQuantite());
            $ficheTechnique->setNbstockproduit($nbstockproduit);
            $ficheTechnique->setEvenement($evenement);

            $entityManager->persist($ficheTechnique);
            $entityManager->flush();
            return $this->redirectToRoute('evenement_show', ['id' => $evenement->getId()]);

        }

        foreach($ficheTechniques as $ficheTechnique) {
            if($ficheTechnique->getNbstockproduit() < $nbpanierfinale){
               $nbpanierfinale =  $ficheTechnique->getNbstockproduit();
              // dd($nbpanierfinale);
            }
        }
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
            'adherents' => $adherents,
            'nbpanierfinale'=>$nbpanierfinale,
            'formfichetechnique' => $formfichetechnique->createView(),
            'ficheTechniques' => $ficheTechniques,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="evenement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Evenement $evenement, FicheTechnique $ficheTechnique, EntityManagerInterface $entityManager, FicheTechniqueRepository $fichetechniqueRepository): Response
    {
        $ficheTechniques = $fichetechniqueRepository->findByEvenement($evenement);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        $formfichetechnique = $this->createForm(FicheTechniqueType::class, $ficheTechnique);
        $formfichetechnique->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($formfichetechnique->isSubmitted() && $formfichetechnique->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('evenement_edit', ['id' => $evenement->getId()]);
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'ficheTechniques' => $ficheTechniques,
            'formfichetechnique' => $formfichetechnique,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="evenement_delete")
     */
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($evenement);
        $entityManager->flush();

        return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
