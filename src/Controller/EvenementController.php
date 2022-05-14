<?php

namespace App\Controller;
use App\Controller\JsonResponse;
use App\Entity\Evenement;
use App\Entity\Adherent;
use App\Entity\TypeEvenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use App\Repository\OperationFinanciereRepository;
use App\Repository\AdherentRepository;
use App\Repository\TypeEvenementRepository;
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
    /* *
  * @Route("/adherent/ajax")
*/
public function ajaxAction(Request $request) {
    $adherents = $this->getDoctrine()
       ->getRepository('AppBundle:Adherent')
       ->findAll();
 
    if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
       $jsonData = array();
       $idx = 0;
       foreach($adherents as $adherent) {
          $adh = array(
             'id' => $adherent->getId(),
             'nom' => $adherent->getNom(),
             'cin' => $adherent->getCin(),
             'adresse' => $adherent->getAdresse(),
             'telephone' => $adherent->getTelephone(),
             'etatcivil' => $adherent->getEtatcivil(),
             'nombrefamille' => $adherent->getNombrefamille(),
             'logement' => $adherent->getLogement(),
             'prixlocal' => $adherent->getPrixlocal(),
             'nombrechambre' => $adherent->getNombrechambre(),
             'electricite' => $adherent->getElectricite(),
             'eau' => $adherent->getEau(),
             'handicap' => $adherent->getHandicap(),
             'typehandicap' => $adherent->getTypehandicap(),
             'maladiechronique' => $adherent->getMaladiechronique(),
             'typemaladiechronique' => $adherent->getTypemaladiechronique(),
             'montantrevenu' => $adherent->getMontantrevenu(),
             'source' => $adherent->getSource(),
             'resume' => $adherent->getResume(),
             'demande' => $adherent->getDemande(),
             'statut' => $adherent->getStatut(),
             'etatreunion' => $adherent->getReunion(),
             'createdAt' => $adherent->getCreatedat(),
             'updatedAt' => $adherent->getApdatedat(),
          );
          $jsonData[$idx++] = $adh;
       }
       return new JsonResponse($jsonData);
    } else {
       return $this->render('adherent/ajaxl.twig');
    }
 }

    /**
     * @Route("/new", name="evenement_new", methods={"GET", "POST"})
     */
    function new (Request $request, EntityManagerInterface $entityManager,TypeEvenementRepository $TypeEvenementRepository,AdherentRepository $AdherentRepository): Response {
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
     * @Route("/{id}", name="evenement_show", methods={"GET"})
     */
    public function show(Evenement $evenement,OperationFinanciereRepository $OperationFinanciereRepository): Response
    {
        $operations = $OperationFinanciereRepository->findByEvenement($evenement);
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
            'operations' => $operations,
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
     * @Route("/delete/{id}", name="evenement_delete", methods={"POST"})
     */
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($evenement);
        $entityManager->flush();

        return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}
