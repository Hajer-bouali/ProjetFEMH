<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateFormType;
use App\Form\User\EditFormType;
use App\Form\EditPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/home")
 * @Security("is_granted('ROLE_SOCIAL') or is_granted('ROLE_ADMIN') or is_granted('ROLE_FINANCIER')")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    /**
     * @Route("/create", name="user_create")
     *
     */
    function new (Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response {
        $user = new User();
        $formCreerProfil = $this->createForm(CreateFormType::class, $user);
        if (!$this->isGranted('ROLE_ADMIN')) {
            $formCreerProfil->remove('roles');
        }
        $formCreerProfil->handleRequest($request);


        if ($formCreerProfil->isSubmitted() && $formCreerProfil->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $formCreerProfil->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('user_index');

        }

        return $this->render('user/create.html.twig', [
            'formCreerProfil' => $formCreerProfil->createView(),
        ]);
    }

    /**
     * @Route("/edit/{user}", name="user_edit")
     */
    public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasher, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $formedituser = $this->createForm(EditFormType::class, $user);
        if (!$this->isGranted('ROLE_ADMIN')) {
            $formedituser->remove('roles');
        }
        $formedituser->handleRequest($request);
       
        $formPassword = $this->createForm(EditPasswordType::class, $user);
        $formPassword->handleRequest($request);

        if ($formedituser->isSubmitted() && $formedituser->isValid()) {
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('user_index');
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
            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'formedituser' => $formedituser->createView(),
            'formPassword' => $formPassword->createView(),
        ]);
    }
    /**
     * @Route("/delete/{user}", name="user_delete")
     *
     */
    public function delete(User $user)
    {
        $em = $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('user_index');

    }

}
