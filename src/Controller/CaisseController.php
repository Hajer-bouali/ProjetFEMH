<?php

namespace App\Controller;

use App\Entity\Caisse;
use App\Entity\TypeCaisse;
use App\Form\CaisseType;
use App\Repository\CaisseRepository;
use App\Repository\TypeCaisseRepository;
use App\Repository\OperationFinanciereDonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/caisse")
 */
class CaisseController extends AbstractController
{
    /**
     * @Route("/", name="caisse_index", methods={"GET"})
     */
    public function index(CaisseRepository $caisseRepository): Response
    {
        return $this->render('caisse/index.html.twig', [
            'caisses' => $caisseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="caisse_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,TypeCaisseRepository $TypeCaisseRepository): Response
    {
        $typeCaisses= $TypeCaisseRepository->findAll();
        $caisse = new Caisse();
        $form = $this->createForm(CaisseType::class, $caisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $caisse->setMontant('0');
            $entityManager->persist($caisse);
            $listeTypeCaisse=$request->request->get("listeTypeCaisse");
            foreach ($listeTypeCaisse as $typeCaisse_id) {
                $typeCaisse= new TypeCaisse();
                $typeCaisse=$TypeCaisseRepository->find($typeCaisse_id);
                $caisse->setTypeCaisse($typeCaisse);   
             }
            $entityManager->flush();

            return $this->redirectToRoute('caisse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('caisse/new.html.twig', [
            'caisse' => $caisse,
            'typeCaisses'=>$typeCaisses,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="caisse_show", methods={"GET"})
     */
    public function show(Caisse $caisse,OperationFinanciereDonRepository $OperationFinanciereDonRepository): Response
    {
        $operations = $OperationFinanciereDonRepository->findBycaisse($caisse);
        return $this->render('caisse/show.html.twig', [
            'caisse' => $caisse,
            'operations'=>$operations,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="caisse_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Caisse $caisse, EntityManagerInterface $entityManager,TypeCaisseRepository $TypeCaisseRepository ): Response
    {
        $typeCaisses= $TypeCaisseRepository->findAll();
        $form = $this->createForm(CaisseType::class, $caisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listeTypeCaisse=$request->request->get("listeTypeCaisse");
            foreach ($listeTypeCaisse as $typeCaisse_id) {
                $typeCaisse= new TypeCaisse();
                $typeCaisse=$TypeCaisseRepository->find($typeCaisse_id);
                $caisse->setTypeCaisse($typeCaisse);   
             }
            $entityManager->flush();

            return $this->redirectToRoute('caisse_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('caisse/edit.html.twig', [
            'caisse' => $caisse,
            'typeCaisses'=>$typeCaisses,
            
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
