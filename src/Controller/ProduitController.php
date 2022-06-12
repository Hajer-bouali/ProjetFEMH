<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\TypeProduit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\TypeProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FicheTechniqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Route("/produit")
 * @Security("is_granted('ROLE_SOCIAL') or is_granted('ROLE_ADMIN')")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="produit_index", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository, FicheTechniqueRepository $fichtechniqueRepository, EntityManagerInterface $entityManager): Response
    {

        $produit = new Produit();
        $fichtechniques = $fichtechniqueRepository->findAll();
        foreach ($fichtechniques as $fichtechnique) {
            if ($fichtechnique->getEvenement()->getEtat() === 'valide') {
                $evenement= $fichtechnique->getEvenement();
                $nbadherent= count($evenement->getAdherent());
                $quantiteFichTechnique= $fichtechnique->getQuantite();
                $nbpanierfinale= $fichtechnique->getEvenement()->getNbpanierfinale();
                $quantiteProduit=$fichtechnique->getProduit()->getQuantite(); 
                $quantitefinale= $nbpanierfinale * $quantiteFichTechnique ;
                $produit->setQuantite($quantiteProduit - $quantitefinale);
                
           
            }
            
        }
        
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="produit_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, TypeProduitRepository $TypeProduitRepository): Response
    {
        $typeProduits=$TypeProduitRepository->findAll();
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);

            $listeTypeProduit=$request->request->get("listeTypeProduit");
            foreach ($listeTypeProduit as $typeProduit_id) {
                $typeProduit= new TypeProduit();
                $typeProduit=$TypeProduitRepository->find($typeProduit_id);
                $produit->setTypeProduit($typeProduit);   
             }
             $entityManager->flush();
            return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'typeProduits' => $typeProduits,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="produit_show", methods={"GET", "POST"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="produit_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager, TypeProduitRepository $TypeProduitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        $typeProduits=$TypeProduitRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $listeTypeProduit=$request->request->get("listeTypeProduit");
            foreach ($listeTypeProduit as $typeProduit_id) {
                $typeProduit= new TypeProduit();
                $typeProduit=$TypeProduitRepository->find($typeProduit_id);
                $produit->setTypeProduit($typeProduit);   
             }
            $entityManager->flush();

            return $this->redirectToRoute('produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
            'typeProduits'=>$typeProduits,
        ]);
    }

    /**
     * @Route("/delete/{produit}", name="produit_delete")
     */
    public function delete( Produit $produit,EntityManagerInterface $entityManager)
    {
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('produit_index');
    }
}
