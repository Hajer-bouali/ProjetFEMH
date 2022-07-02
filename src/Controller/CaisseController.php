<?php

namespace App\Controller;

use App\Entity\Caisse;
use App\Entity\TypeCaisse;
use App\Form\CaisseType;
use App\Repository\CaisseRepository;
use App\Repository\OperationFinanciereRepository;
use App\Services\ServiceChiffreCaisse;
use App\Repository\TypeCaisseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/caisse")
 * @Security("is_granted('ROLE_FINANCIER') or is_granted('ROLE_ADMIN')")
 */
class CaisseController extends AbstractController
{
    /**
     * @Route("/", name="caisse_index", methods={"GET"})
     */
    public function index(CaisseRepository $caisseRepository, OperationFinanciereRepository $OperationFinanciereRepository): Response
    {
        $caisse = new Caisse();
        $operations = $OperationFinanciereRepository->findByCaisse($caisse);
        foreach ($operations as $operation) {
            if ($operation->getTypeoperation() === 'don' && $operation->getEtat() === 'valide') {
                $operationmontant = $operation->getMontant();
                $caissemontant = $caisse->getMontant();
                $caisse->setMontant($caissemontant + $operationmontant);
            }
            if ($operation->getTypeoperation() === 'aide' && $operation->getEtat() === 'valide') {
                $operationmontant = $operation->getMontant();
                $caissemontant = $caisse->getMontant();
                $caisse->setMontant($caissemontant - $operationmontant);
            }
        }
        return $this->render('caisse/index.html.twig', [
            'caisses' => $caisseRepository->findAll(),
            'operations' => $operations,
        ]);
    }
    /**
     * @Route("/dashboard", name="caisse_dashboard", methods={"GET" , "POST"})
     */
    public function dashboard(Request $request, OperationFinanciereRepository $OperationFinanciereRepository, EntityManagerInterface $entityManager, ServiceChiffreCaisse $serviceChiffrecaisse): Response
    {
        $operations = $OperationFinanciereRepository->findAll();
        $operationdonmontant = 0;
        $operationaidemontant = 0;
        $montanttotal = 0;
        $date = (new \DateTime('now'));
        $date->setTime(0, 0);

        foreach ($operations as $operation) {
            $dateoperation = $operation->getDate();
            if ($operation->getEtat() === 'valide' ) {
                $operation->getTypeoperation() === 'don' ?
                $operationdonmontant += $operation->getMontant() :
                $operationaidemontant += $operation->getMontant();
            }
            if ($operation->getEtat() === 'valide') {
                $montanttotal += $operation->getMontant();
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

            $caisse = $request->request->get('caisse', '1'); 

            $tabaide = $serviceChiffrecaisse->ChiffreCaisseParMois($datedebut, $datefin, $caisse, 'aide');
            $tabdon = $serviceChiffrecaisse->ChiffreCaisseParMois($datedebut, $datefin, $caisse, 'don');
        }

        $caisses = $entityManager->getRepository(Caisse::class)->findAll();

        return $this->render('caisse/dashboard.html.twig', [
            'tabaide' => $tabaide,
            'tabdon' => $tabdon,
            'operationdonmontant' => $operationdonmontant,
            'operationaidemontant' => $operationaidemontant,
            'montanttotal' => $montanttotal,
            'caisses' => $caisses,
        ]);
    }

    /**
     * @Route("/new", name="caisse_new", methods={"GET", "POST"})
     */
    function new (Request $request, EntityManagerInterface $entityManager, TypeCaisseRepository $TypeCaisseRepository): Response {
        $typeCaisses = $TypeCaisseRepository->findAll();
        $caisse = new Caisse();
        $form = $this->createForm(CaisseType::class, $caisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($caisse);
            $listeTypeCaisse = $request->request->get("listeTypeCaisse");
            foreach ($listeTypeCaisse as $typeCaisse_id) {
                $typeCaisse = new TypeCaisse();
                $typeCaisse = $TypeCaisseRepository->find($typeCaisse_id);
                $caisse->setTypeCaisse($typeCaisse);
            }
            $entityManager->flush();

            return $this->redirectToRoute('caisse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('caisse/new.html.twig', [
            'caisse' => $caisse,
            'typeCaisses' => $typeCaisses,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="caisse_show", methods={"GET", "POST"})
     */
    public function show(Caisse $caisse, CaisseRepository $caisseRepository): Response
    {
        $caisseRepository->updateMontant($caisse);
        return $this->render('caisse/show.html.twig', [
            'caisse' => $caisse,
            'operations' => $caisse->getOperationFinancieres(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="caisse_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Caisse $caisse, EntityManagerInterface $entityManager, TypeCaisseRepository $TypeCaisseRepository): Response
    {
        $typeCaisses = $TypeCaisseRepository->findAll();
        $form = $this->createForm(CaisseType::class, $caisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listeTypeCaisse = $request->request->get("listeTypeCaisse");
            foreach ($listeTypeCaisse as $typeCaisse_id) {
                $typeCaisse = new TypeCaisse();
                $typeCaisse = $TypeCaisseRepository->find($typeCaisse_id);
                $caisse->setTypeCaisse($typeCaisse);
            }
            $entityManager->flush();

            return $this->redirectToRoute('caisse_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('caisse/edit.html.twig', [
            'caisse' => $caisse,
            'typeCaisses' => $typeCaisses,

            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="caisse_delete")
     */
    public function delete(Request $request, Caisse $caisse, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($caisse);
        $entityManager->flush();

        return $this->redirectToRoute('caisse_index', [], Response::HTTP_SEE_OTHER);
    }

}
