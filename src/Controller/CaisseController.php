<?php

namespace App\Controller;

use App\Entity\Caisse;
use App\Entity\TypeCaisse;
use App\Form\CaisseType;
use App\Repository\CaisseRepository;
use App\Repository\TypeCaisseRepository;
use App\Repository\OperationFinanciereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/caisse")
 * @Security("is_granted('ROLE_FINANCIER') or is_granted('ROLE_ADMIN')")
 */
class CaisseController extends AbstractController
{
    /**
     * @Route("/", name="caisse_index", methods={"GET"})
     */
    public function index(CaisseRepository $caisseRepository,OperationFinanciereRepository $OperationFinanciereRepository): Response
    {
        $caisse = new Caisse();
        $operations = $OperationFinanciereRepository->findByCaisse($caisse);
        foreach($operations as $operation){
            if($operation->getTypeoperation()==='don'&& $operation->getEtat()==='valide'){
                $operationmontant=$operation->getMontant();
                $caissemontant=$caisse->getMontant();
                $caisse->setMontant($caissemontant+$operationmontant);
            }
            if($operation->getTypeoperation()==='aide'&& $operation->getEtat()==='valide'){
                $operationmontant=$operation->getMontant();
               $caissemontant=$caisse->getMontant();
                $caisse->setMontant($caissemontant-$operationmontant);
            }
        }
        return $this->render('caisse/index.html.twig', [
            'caisses' => $caisseRepository->findAll(),
            'operations'=>$operations,
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
     * @Route("/{id}", name="caisse_show", methods={"GET", "POST"})
     */
    public function show(Caisse $caisse, CaisseRepository $caisseRepository): Response
    {
        $caisseRepository->updateMontant($caisse);
        return $this->render('caisse/show.html.twig', [
            'caisse' => $caisse,
            'operations'=> $caisse->getOperationFinancieres(),
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
