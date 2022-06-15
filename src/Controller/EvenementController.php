<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\Evenement;
use App\Entity\Produit;
use App\Entity\FicheTechnique;
use App\Form\EvenementType;
use App\Form\FicheTechniqueType;
use App\Repository\AdherentRepository;
use App\Repository\EvenementRepository;
use App\Repository\FicheTechniqueRepository;
use App\Repository\CaisseRepository;
use App\Repository\OperationFinanciereRepository;
use App\Services\ServiceChiffreEvenement;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/evenement")
 * @Security("is_granted('ROLE_SOCIAL') or is_granted('ROLE_ADMIN')")

 */
class EvenementController extends AbstractController
{
    /**
     * @Route("/", name="evenement_index", methods={"GET", " POST"})
     */
    public function index(EvenementRepository $evenementRepository): Response
    {   
        

        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
        ]);
    }
        /**
     * @Route("/dashboard", name="evenement_dashboard", methods={"GET" , "POST"})
     */
    public function dashboard(Request $request, AdherentRepository $adherentRepository, EntityManagerInterface $entityManager, OperationFinanciereRepository $OperationFinanciereRepository,ServiceChiffreEvenement $serviceChiffreevenement,EvenementRepository $evenementRepository): Response
    {
        $adherents = $adherentRepository->findAll();
        $nbAdherentAccepte = 0;
        $nbAdherentrefuse= 0;
        $nbAdherentreporte = 0;
        $nbAdherentEncour = 0;
        $date = (new \DateTime('now'));
        $date->setTime(0, 0);
        foreach($adherents as $adherent){
            $dateAdherent = $adherent->getDate();
            if ($adherent->getEtatreunion() === 'valide' && $dateAdherent == $date) {
               $nbAdherentAccepte += 1; 
            }
            if ($adherent->getEtatreunion() === 'refuse' && $dateAdherent == $date) {
                $nbAdherentrefuse += 1;
            }
            if ($adherent->getEtatreunion() === 'reporte' && $dateAdherent == $date) {
                $nbAdherentreporte += 1;
            }
            if ($adherent->getEtatreunion() === 'Encours' && $dateAdherent == $date) {
                $nbAdherentEncour += 1;
            }
        }
        $entityManager->flush();
        $tabaide = [];
        $tabdon = [];

        if ($request->isMethod('post')) {
            $datedebut = \DateTime::createFromFormat('Y-m', $request->request->get('datedebut'));
            $datefin = \DateTime::createFromFormat('Y-m', $request->request->get('datefin'));
            
            $datedebut->setDate($datedebut->format('Y'), $datedebut->format('m'), 1);
            $datefin->setDate($datefin->format('Y'), $datefin->format('m'), 1);
            $evenement = $request->request->get('evenement', '1'); 

            $tabaide = $serviceChiffreevenement->ChiffreEvenementParMois($datedebut, $datefin, $evenement, 'aide');
            $tabdon = $serviceChiffreevenement->ChiffreEvenementParMois($datedebut, $datefin, $evenement, 'don');
        }

        $evenements = $evenementRepository->findAll();
        return $this->render('evenement/dashboard.html.twig', [
            'nbAdherentEncour'=> $nbAdherentEncour,
            'nbAdherentreporte'=> $nbAdherentreporte,
            'nbAdherentrefuse'=> $nbAdherentrefuse,
            'nbAdherentAccepte'=> $nbAdherentAccepte,
            'tabaide' => $tabaide,
            'tabdon' => $tabdon,
            'evenements' => $evenements,
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
    public function show(Request $request, Evenement $evenement, AdherentRepository $adherentRepository, EntityManagerInterface $entityManager, FicheTechniqueRepository $fichetechniqueRepository): Response
    {
        $adherents = $adherentRepository->findAll();
        $ficheTechniques = $fichetechniqueRepository->findByEvenement($evenement);
        $ficheTechnique = new FicheTechnique();
        $nbpanierfinale = 10000;

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

            foreach ($ficheTechniques as $ficheTechnique) {
                if ($ficheTechnique->getNbstockproduit() < $nbpanierfinale) {
                    $nbpanierfinale = $ficheTechnique->getNbstockproduit();
                }
            }
            $evenement->setNbpanierfinale($nbpanierfinale);
            $entityManager->getRepository(Produit::class)->updateQuantiteProduit($produit);
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('evenement_show', ['id' => $evenement->getId()]);
        }

        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
            'adherents' => $adherents,
            'nbpanierfinale' => $nbpanierfinale,
            'formfichetechnique' => $formfichetechnique->createView(),
            'ficheTechniques' => $ficheTechniques,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="evenement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);          
        }

        return $this->renderForm('evenement/edit.html.twig', [
            'evenement' => $evenement,
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

    /**
     * @Route("/{id}/adherents", name="evenement_adherents", methods={"GET", "POST"})
     */
    public function adherents(Request $request, Evenement $evenement, EntityManagerInterface $entityManager, AdherentRepository $adherentRepository): Response
    {
        if ($request->isMethod('POST')) {
            //supprimer tous les anciens adherents
            foreach ($evenement->getAdherent() as $adherent) {
                $evenement->removeAdherent($adherent);
            }

            //recuperer la liste des adherents qui respectent les critères de recherche
            $adherentsList = $adherentRepository->findByCriteres($request->request->all());
            //affectation de la liste des adherents a l'évènement selectionné
            foreach ($adherentsList as $adherent) {
                $evenement->addAdherent($adherent);

                // condition sur le nb max de adherent il ne faut pas depasse nbpanierfinal
                if ($evenement->getNbpanierfinale() <= count($evenement->getAdherent())) {
                    $evenement->setCriteres($request->request->all());
                    $entityManager->persist($evenement);
                    $entityManager->flush();
                    $this->addFlash('warning', 'vous avez dépassé le nombre des bénéficiaires possible ! le stock est insuffisant');
                    return $this->redirectToRoute('evenement_adherents', ['id' => $evenement->getId()]);
                }

            }

            //sauvgarde des critères de recherche dans la table evenement
            $evenement->setCriteres($request->request->all());

            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('evenement_adherents', ['id' => $evenement->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/adherents.html.twig', [
            'evenement_id' => $evenement->getId(),
            'adherents' => $evenement->getAdherent(),
            'criteres' => $evenement->getCriteres(),
        ]);
    }

    /**
     * @Route("/{evenement}/{adherent}/delete", name="evenement_delete_adherent", methods={"GET"})
     */
    public function deleteAdherents(Request $request, Evenement $evenement, Adherent $adherent, EntityManagerInterface $entityManager, AdherentRepository $adherentRepository): Response
    {
        $evenement->removeAdherent($adherent);
        $entityManager->persist($evenement);
        $entityManager->flush();
        return $this->redirectToRoute('evenement_adherents', ['id' => $evenement->getId()], Response::HTTP_SEE_OTHER);
    }
}
