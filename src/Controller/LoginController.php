<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(): Response
    {
        return $this->render('login/dashboard.html.twig');
    }

    /**
     * @Route("/", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            if (count($this->getUser()->getRoles()) > 1) {
                    return $this->redirectToRoute('login_dashboard');
                

            } else {
                return $this->redirectToRoute('dashboard');
            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,

        ]);
    }
}
