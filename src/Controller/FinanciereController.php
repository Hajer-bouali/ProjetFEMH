<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

 /**
* @IsGranted("ROLE_FINANCIERE","ROLE_ADMIN")

*/

class FinanciereController extends AbstractController
{
    /**
     * @Route("/financiere", name="financiere")
     */
    public function index(): Response
    {
        return $this->render('financiere/index.html.twig', [
            'controller_name' => 'FinanciereController',
        ]);
    }
}