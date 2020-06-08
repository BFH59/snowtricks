<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Form\RegistrationType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Manage user authentification
     * @Route("/login", name="account_login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * manage user logout
     * @Route("/logout", name="account_logout")
     */
    public function logout()
    {

    }

    /**
     * Show registration form and handle it
     * @Route("/register", name="account_register")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordEncoderInterface $encoder
     * @param FileUploader $fileUploader
     * @return Response
     */

    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, FileUploader $fileUploader)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $form['avatar']->getData();
            if ($avatar) {
                $avatarFileName = $fileUploader->upload($avatar);
                $user->setAvatar($avatarFileName);
            }

            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé !"
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param User $user
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager, FileUploader $fileUploader)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $form['avatar']->getData();
            if ($avatar) {
                $avatarFileName = $fileUploader->upload($avatar);
                $user->setAvatar($avatarFileName);
            }
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de votre ont bien été enregistrées !"
            );

            return $this->redirectToRoute('account_profile');
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
