<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AdherentRepository;
use App\Repository\CaisseRepository;
use App\Repository\OperationFinanciereRepository;
use App\Services\ServiceChiffreCaisse;
use App\Services\ServiceChiffreEvenement;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Annotation\Route;

/*/**
 * @Route("home")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="profil_index")
     */
    public function index(): Response
    {
        if ($this->isGranted("ROLE_SOCIAL")) {
            return $this->redirectToRoute('evenement_dashboard');
        }
        if ($this->isGranted("ROLE_FINANCIER")) {
            return $this->redirectToRoute('caisse_dashboard');
        }
        if ($this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('dashboard_Admin');
        }
    }

    /**
     * @Route("/dashboardAdminFinancier", name="dashboard_Admin_Financier")
     */
    public function dashboardAdminFinancier (Request $request,CaisseRepository $CaisseRepository, EntityManagerInterface $entityManager, 
    OperationFinanciereRepository $OperationFinanciereRepository, ServiceChiffreCaisse $serviceChiffrecaisse )
    {
        
    $operations = $OperationFinanciereRepository->findAll();
    $operationdonmontant = 0;
    $operationaidemontant = 0;
    $montanttotal = 0;
    $date = (new \DateTime('now'));
    $date->setTime(0, 0);

    foreach ($operations as $operation) {
        $dateoperation = $operation->getDate();
        if ($operation->getEtat() === 'valide' && $dateoperation == $date) {
            $operation->getTypeoperation() === 'don' ?
            $operationdonmontant += $operation->getMontant() :
            $operationaidemontant += $operation->getMontant();
        }
        if ($operation->getEtat() === 'valide' && $dateoperation == $date) {
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
    $caisses = $CaisseRepository->findAll();
    return $this->render('Admin/dashboardAdminFinancier.html.twig', [
        'tabaide' => $tabaide,
        'tabdon' => $tabdon,
        'operationdonmontant' => $operationdonmontant,
        'operationaidemontant' => $operationaidemontant,
        'montanttotal' => $montanttotal,
        'caisses' => $caisses]);
    }

     /**
     * @Route("/dashboardAdminSocial", name="dashboard_Admin_Social")
     */
    public function dashboardAdminSocial(Request $request, AdherentRepository $adherentRepository, EntityManagerInterface $entityManager, OperationFinanciereRepository $OperationFinanciereRepository,ServiceChiffreEvenement $serviceChiffreevenement,EvenementRepository $evenementRepository): Response
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
            if ($adherent->getEtatreunion() === 'valide') {
               $nbAdherentAccepte += 1; 
            }
            if ($adherent->getEtatreunion() === 'refuse' ) {
                $nbAdherentrefuse += 1;
            }
            if ($adherent->getEtatreunion() === 'reporte') {
                $nbAdherentreporte += 1;
            }
            if ($adherent->getEtatreunion() === 'Encours') {
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
     * @Route("/dashboardAdmin", name="dashboard_Admin")
     */
    public function dashboardAdmin(Request $request): Response
    {
        return $this->render('dashboardAdmin.html.twig');
    } 
}
