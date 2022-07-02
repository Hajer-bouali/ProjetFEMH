<?php

namespace App\Controller;
use App\Form\EditPasswordType;
use App\Form\EditProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("profil")
 */
class ProfilController extends AbstractController
{
    /**
     * @Route("/", name="login_dashboard")
     */
    public function role(): Response
    {
        if ($this->isGranted("ROLE_SOCIAL")) {
            return $this->redirectToRoute('evenement_dashboard');
        }
        if ($this->isGranted("ROLE_FINANCIER")) {
            return $this->redirectToRoute('caisse_dashboard');
        }
        if ($this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute('user_index');
        }
    }
    /**
     * @Route("/", name="profil_index")
     */
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    /**
     * @Route("/profil/modifier", name="user_profil_modifier")
     */
    public function editProfile(Request $request, UserPasswordHasherInterface $userPasswordHasher)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(EditProfilType::class, $user);
        $form->handleRequest($request);

        $formPassword = $this->createForm(EditPasswordType::class, $user);
        $formPassword->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('profil_index');
        }
        if ($formPassword->isSubmitted() && $formPassword->isValid()) {

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $formPassword->get('plainPassword')->getData()
                )
            );
            $em->persist($user);
            $em->flush();
            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('profil_index');
        }

        return $this->render('profil/editprofil.html.twig', [
            'form' => $form->createView(),
            'formPassword' => $formPassword->createView(),
        ]);
        return $this->render('base.html.twig', [
            'form' => $form->createView(),
            'formPassword' => $formPassword->createView(),
            'user' => $user,
        ]);
    }
    /**
     * @Route("/Profil", name="profil_non_valide")
     */
    public function profilNonValide(): Response
    {
        return $this->render('profil/profilNonValide.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
}
