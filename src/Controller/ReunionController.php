<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\Decision;
use App\Entity\Reunion;
use App\Form\ReunionType;
use App\Repository\AdherentRepository;
use App\Repository\DecisionRepository;
use App\Repository\ReunionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/reunion")
 * @Security("is_granted('ROLE_SOCIAL') or is_granted('ROLE_ADMIN')")
 */
class ReunionController extends AbstractController
{
    /**
     * @Route("/", name="reunion_index", methods={"GET"})
     */
    public function index(ReunionRepository $reunionRepository): Response
    {
        return $this->render('reunion/index.html.twig', [
            'reunions' => $reunionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/create", name="reunion_create", methods={"GET", "POST"})
     */
    function new (Request $request, EntityManagerInterface $entityManager, AdherentRepository $AdherentRepository): Response {
        $adherents = $AdherentRepository->findByEtatreunion('Encours');
        $reunion = new Reunion();

        $form = $this->createForm(ReunionType::class, $reunion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reunion);

            $listeadherents = $request->request->get("listeadherents");
            foreach ($listeadherents as $idadherent) {
                $decision = new Decision();
                $adherent = $AdherentRepository->find($idadherent);
                $decision->setReunion($reunion);
                $decision->setAdherent($adherent);
                $decision->setStatut("Encours");
                $adherent->setEtatreunion("Non traitée");
                $decision->setDetail("Non traitée");
                $entityManager->persist($decision);
            }
            $entityManager->flush();
            return $this->redirectToRoute('reunion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reunion/new.html.twig', [
            'reunion' => $reunion,
            'form' => $form,
            'adherents' => $adherents,
        ]);
    }

    /**
     * @Route("/{id}", name="reunion_show", methods={"GET", "POST"})
     */
    public function show(Request $request, EntityManagerInterface $entityManager, Reunion $reunion, DecisionRepository $decisionRepository, AdherentRepository $AdherentRepository): Response
    {

        if ($listeadherents = $request->request->get("listeadherents")) {
            foreach ($listeadherents as $idadherent) {
                $decision = new Decision();
               
                $adherent = $AdherentRepository->find($idadherent);
                $decision->setReunion($reunion);

                $decision->setAdherent($adherent);
                $decision->setDetail("Non traitée");
                $decision->setStatut("Encours");
                $entityManager->persist($decision);
                $entityManager->persist($adherent);
            }
        }

        $entityManager->flush();

        $adherents = $AdherentRepository->findByetatreunion('Encours');
        $decisions = $decisionRepository->findByreunion($reunion);
        return $this->render('reunion/show.html.twig', [
            'reunion' => $reunion,
            'decisions' => $decisions,
            'listeadherents'=>$listeadherents,
            'adherents' => $adherents,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reunion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reunion $reunion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReunionType::class, $reunion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reunion_index');
        }

        return $this->renderForm('reunion/edit.html.twig', [
            'reunion' => $reunion,
            'form' => $form,

        ]);
    }

    /**
     * @Route("/delete/{id}", name="reunion_delete")
     */
    public function delete( Reunion $reunion, EntityManagerInterface $em): Response
    {
        $em->remove($reunion);
        $em->flush();
        return $this->redirectToRoute('reunion_index');
    }
    /**
     * @Route("/deletedossier/{id}", name="dossier_delete")
     */
    public function deletedossier(Decision $decision): Response
    {
        $em = $em = $this->getDoctrine()->getManager();
        $idreunion = $decision->getReunion()->getId();
        $em->remove($decision);

        $em->flush();
        return $this->redirectToRoute('reunion_show', ['id' => $idreunion]);
    }
    /**
     * @Route("/dossierstatut/{id}", name="decision_statut")
     */
    public function Statutdossier(Decision $decision, Request $request, EntityManagerInterface $entityManager, AdherentRepository $AdherentRepository): Response
    {
        $idreunion = $decision->getReunion()->getId();
        $detail = $request->request->get("detail");
        $statut = $request->request->get("statut");

                $decision->setStatut($statut);
                $adherent= $decision->getAdherent();
                $adherent->setEtatreunion($statut); 
                $decision->setDetail($detail);
                $entityManager->persist($decision);
        $entityManager->flush();
        return $this->redirectToRoute('reunion_show', [
            'id' => $idreunion,

        ]);
    }
}