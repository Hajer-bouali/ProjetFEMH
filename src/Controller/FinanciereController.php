<?php

namespace App\Controller;

use App\Services\ServiceChiffreCaisse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

 

class FinanciereController extends AbstractController
{
    /**
     * @Route("/financiere", name="financiere")
     */
    public function index(ServiceChiffreCaisse $serviceChiffreciasse): Response
    {
        $date1 = new \DateTime('last year');
        $date2 = new \DateTime('now');
        $serviceChiffreciasse->ChiffreCaisseParMois($date1, $date2, 1, 'don');
        return $this->render('financiere/index.html.twig', [
            'controller_name' => 'FinanciereController',
        ]);
    }
}