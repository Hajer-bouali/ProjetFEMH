<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Entity\Benificiaire;
use App\Entity\Revenufamilial;
use App\Entity\Decision;
use App\Entity\PiecesJointes;
use App\Form\PiecesJointesType;
use App\Form\AdherentType;
use App\Form\BenificiaireType;
use App\Form\RevenufamilialType;
use App\Repository\AdherentRepository;
use App\Repository\DecisionRepository;
use App\Repository\BenificiaireRepository;
use App\Repository\RevenufamilialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;


/**
 * @Route("/adherent")
 * @Security("is_granted('ROLE_SOCIAL') or is_granted('ROLE_ADMIN')")
 */
class AdherentController extends AbstractController
{
    /**
     * @Route("/", name="adherent_index", methods={"GET"})
     */
    public function index(AdherentRepository $adherentRepository): Response
    {
        return $this->render('adherent/index.html.twig', [
            'adherents' => $adherentRepository->findByStatut('actif'),
        ]);
    }

    /**
     * @Route("/{adherent}/pdf", name="adherent_pdf", methods={"GET"})
     */
    public function pdfadherent(Request $request, Adherent $adherent, Revenufamilial $revenufamilial, BenificiaireRepository $benificiaireRepository, RevenufamilialRepository $revenufamilialRepository,EntityManagerInterface $entityManager)
    {
        $revenufamilials = $revenufamilialRepository->findByAdherent($adherent);
        $benificiaires = $benificiaireRepository->findByAdherent($adherent);
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        //$dompdf = new Dompdf(array('enable_remote' => true));

        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $pdfOptions->set('IsFontSubsettingEnabled', true);
        $pdfOptions->set('IsHtml5ParserEnabled', true);

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->setOptions($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('adherent/pdf.html.twig', [
            'adherent' => $adherent,
            'revenufamilials' =>$revenufamilials,
            'benificiaires'=>$benificiaires,
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
        
        // Render the HTML as PDF
        $dompdf->render();
        
        $dompdf->output(['isRemoteEnabled' => false]);
        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Dossier de " . $adherent->getNom() . ".pdf", [
            "Attachment" => false
        ]);
        exit(0);
    }

    /**
     * @Route("/archiver", name="adherent_archive", methods={"GET"})
     */
    public function archif(AdherentRepository $adherentRepository): Response
    {
        return $this->render('adherent/archiver.html.twig', [
            'adherents' => $adherentRepository->findByStatut('desactive'),
        ]);
    }
    /**
     * @Route("/valide", name="adherent_accept")
     */
    public function listaccepted( AdherentRepository $adherentRepository): Response
    {
        
        return $this->render('adherent/accepted.html.twig', [
            'adherents' => $adherentRepository->findByStatut('valide'),
        ]);
    }
    
    /**
     * @Route("/refuse", name="adherent_refuse", methods={"GET"})
     */
    public function listrefuse(AdherentRepository $adherentRepository): Response
    {
        return $this->render('adherent/accepted.html.twig', [
            'adherents' => $adherentRepository->findByStatut('refuse'),
        ]);
    }
    /**
     * @Route("/new", name="adherent_new",  methods={"GET", "POST"})
     */
    public function new(Request $request, BenificiaireRepository $benificiaireRepository, EntityManagerInterface $entityManager): Response
    {
        $adherent = new Adherent();
        $form = $this->createForm(AdherentType::class, $adherent);
        $form->handleRequest($request);
        $benificiaire = new Benificiaire();
        $formBen = $this->createForm(BenificiaireType::class, $benificiaire);
        $formBen->handleRequest($request);

        if ($formBen->isSubmitted() && $formBen->isValid()) {
            $benificiaireRepository->add($benificiaire);

            return $this->redirectToRoute('adherent_new', [], Response::HTTP_SEE_OTHER);
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $piecesjointes = $form->get('piecesjointes')->getData();
            //on boucle sur les fichiers
            foreach ($piecesjointes as $piecesjointe) {
                $fichier = md5(uniqid()) . '.' . $piecesjointe->guessExtension();
                //on copie le fichier dans le dossiers uploads
                $piecesjointe->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
                //on stock le fichier dans la base de données(son nom)
                $file = new PiecesJointes();
                $file->setNom($fichier);
                $adherent->addPiecesJointe($file);
            }
            $adherent->setDate(new \DateTime('now'));
            $adherent->setStatut('actif');
            $adherent->setEtatreunion("Encours");
            $adherent->setResponsable($this->getUser()->getName());

            $entityManager->persist($adherent);
            $entityManager->flush();

            return $this->redirectToRoute('adherent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adherent/new.html.twig', [
            'adherent' => $adherent,
            'benificiaire' => $benificiaire,
            'formBen' => $formBen,
            'form' => $form
        ]);
        return $this->renderForm('benificiaire/new.html.twig', [
            'benificiaire' => $benificiaire,
            'formBen' => $formBen,
        ]);
    }
    /**
     * @Route("/{id}", name="adherent_show", methods={"GET", "POST"})
     */
    public function show(Request $request,Adherent $adherent,RevenufamilialRepository $revenufamilialRepository,BenificiaireRepository $benificiaireRepository,EntityManagerInterface $entityManager): Response

    {
        $benificiaires = $benificiaireRepository->findByAdherent($adherent);
        $benificiaire = new Benificiaire();
        $formBen = $this->createForm(BenificiaireType::class, $benificiaire);
        $formBen->handleRequest($request);
        if ($formBen->isSubmitted() && $formBen->isValid()) {
            $benificiaire->setAdherent($adherent);
            $entityManager->persist($benificiaire);
            $entityManager->flush();
            return $this->redirectToRoute('adherent_show', [
                'id' => $adherent->getId(),
            ]);
        }

        $revenufamilials = $revenufamilialRepository->findByAdherent($adherent);
        $revenufamilial =new Revenufamilial();
        $formRF = $this->createForm(RevenufamilialType::class, $revenufamilial);
        $formRF->handleRequest($request);

        if ($formRF->isSubmitted() && $formRF->isValid()) {
            $revenufamilial->setAdherent($adherent);
            $entityManager->persist($revenufamilial);
             $entityManager->flush();
             return $this->redirectToRoute('adherent_show', ['id' => $adherent->getId()]);
        }
        
        $piecesJointes = $adherent->getPiecesJointes();
        return $this->render('adherent/show.html.twig', [
            'adherent' => $adherent,
            'formBen' => $formBen->createView(),
            'benificiaires' => $benificiaires,
            'formRF' => $formRF->createView(),
            'revenufamilials' =>$revenufamilials,
            'piecesJointes' => $piecesJointes,
        ]);
    }
    /**
     * @Route("/{id}/edit/", name="adherent_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Adherent $adherent,AdherentRepository $adherentRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdherentType::class, $adherent);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
           // dd($request->request->get('adherent')['nom'], $adherentRepository->findOneBy(['id' =>$adherent])->getNom());
            
            
            //on recupere les fichiers
            $piecesjointes = $form->get('piecesjointes')->getData();
            //on boucle sur les fichiers
            //$piecesjointes avec s est le nom de table
            foreach ($piecesjointes as $piecesjointe) {
                $fichier = md5(uniqid()) . '.' . $piecesjointe->guessExtension();
                //on copie le fichier dans le dossiers uploads
                $piecesjointe->move(
                    $this->getParameter('image_directory'),
                    $fichier
                );
                //on stock le fichier dans la base de données(son nom)
                $file = new PiecesJointes();
                $file->setNom($fichier);
                $adherent->addPiecesJointe($file);
            }
            $entityManager->flush();

            return $this->redirectToRoute('adherent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adherent/edit.html.twig', [
            'adherent' => $adherent,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="adherent_delete")
     */
    public function delete(Request $request, Adherent $adherent, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($adherent);
        $entityManager->flush();


        return $this->redirectToRoute('adherent_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/archiveradherent/{adherent}/{statut}", name="adherent_archiver")
     */
    public function statut(Adherent $adherent, $statut, EntityManagerInterface $em, AdherentRepository $AdherentRepository): Response
    {
        $em = $em = $this->getDoctrine()->getManager();
        if ($adherent->getStatut() == 'actif') {

            $adherent->setStatut('desactive');
            $em->flush();
            $this->addFlash('success', "Archive effectué avec succès!");
        } else {

            $adherent->setStatut('actif');
            $em->flush();
            $this->addFlash('success', "Desrchive effectué avec succès!");
        }

        return $this->redirectToRoute('adherent_index', ['id' => $adherent]);
    }
    

    /**
     * @Route("/supprimer/piecesJointes/{id}", name="adherent_piecesJointes_delete", methods={"DELETE"})
     */
    public function deletePiecesJointes(PiecesJointes $piecesjointe, Request $request)
    {

        $data = json_decode($request->getContent(), true);
        //on verifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $piecesjointe->getId(), $data['_token'])) {
            //on recupere le nom de fichier
            $nom = $piecesjointe->getNom();
            //on supprime le fichier
            unlink($this->getParameter('image_directory') . '/' . $nom);
            //on supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($piecesjointe);
            $em->flush();
            //on repond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}
