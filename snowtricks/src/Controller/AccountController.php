<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\RegistrationType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

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
     * @param MailerInterface $mailer
     * @param RoleRepository $roleRepository
     * @return Response
     */

    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, FileUploader $fileUploader, MailerInterface $mailer, RoleRepository $roleRepository)
    {
        $user = new User();
        $userRole = $roleRepository->findOneByRoleType('ROLE_USER');

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userMail = $form['email']->getData();
            $name = $form['firstName']->getData();
            $avatar = $form['avatar']->getData();

            if ($avatar) {
                $avatarFileName = $fileUploader->upload($avatar);
                $user->setAvatar($avatarFileName);
            }

            $token = $this->generateToken($userMail);
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setToken($token);
            $user->setHash($hash);

            $userRole->addUser($user);
            $manager->persist($userRole);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé ! Vous allez recevoir un e-mail avec un lien pour valider votre compte !"
            );
            $slug = $user->getSlug();
            $this->sendEmailAccountConfirmation($mailer, $userMail, $name, $slug, $token);

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("account_confirm_email", name="account_confirm_email")
     * @param MailerInterface $mailer
     * @return RedirectResponse
     */
    public function sendBackConfirmationMail(MailerInterface $mailer)
    {
        $user = $this->getUser();
        $userMail = $user->getEmail();
        $name = $user->getFirstName();
        $slug = $user->getSlug();
        $token = $user->getToken();

        if ($user) {
            $this->sendEmailAccountConfirmation($mailer, $userMail, $name, $slug, $token);
            $this->addFlash('success', "L'email de validation a été renvoyé sur votre adresse e-mail !");
            return $this->redirectToRoute('account_profile');
        } else {
            $this->addFlash('danger', 'Une erreur est survenue. Veuillez recommencer.');
        }
    }

    /**
     * @Route("account_activation/{slug}/{token}", name="account_activation")
     *
     * @param UserRepository $repo
     * @param $slug
     * @param $token
     * @param EntityManagerInterface $manager
     * @param RoleRepository $roleRepository
     * @return RedirectResponse
     */
    public function AccountValidation(UserRepository $repo, $slug, $token, EntityManagerInterface $manager, RoleRepository $roleRepository)
    {
        $user = $repo->findOneBySlug($slug);
        $userRole = $roleRepository->findOneByRoleType('ROLE_MEMBER');

        if (!$user) {
            $this->addFlash(
                'danger',
                "La validation de votre compte a échoué."
            );
            return $this->redirectToRoute('account_login');
        }
        if ($token != null && $token === $user->getToken()) {
            if (in_array($userRole->getTitle(), $user->getRoles())) {
                $this->addFlash(
                    'warning',
                    "Ce compte a déjà été validé"
                );
                return $this->redirectToRoute('account_login');
            }
            $userRole->addUser($user);
            $manager->persist($userRole);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre compte a été activé avec succès !"
            );

        } else {
            $this->addFlash(
                'danger',
                "La validation de votre compte a échoué."
            );
        }
        return $this->redirectToRoute('account_login');
    }

    /**
     * @Route("account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param FileUploader $fileUploader
     * @param RoleRepository $roleRepository
     * @param MailerInterface $mailer
     * @param UserRepository $userRepository
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager, FileUploader $fileUploader, RoleRepository $roleRepository, MailerInterface $mailer, UserRepository $userRepository)
    {
        $user = $this->getUser();
        $currentMail = $user->getEmail();
        $form = $this->createForm(AccountType::class, $user);
        if (!in_array('ROLE_MEMBER', $user->getRoles())) {
            $form->remove('email');
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $form['avatar']->getData();
            if ($avatar) {
                $avatarFileName = $fileUploader->upload($avatar);
                $user->setAvatar($avatarFileName);
            }
            if (isset($form['email'])) {
                if ($form['email']->getData() != $currentMail) {
                    $this->updateEmail($this->getUser(), $manager, $roleRepository, $userRepository, $mailer);
                }
            }

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de votre compte ont bien été enregistrées !"
            );

            return $this->redirectToRoute('account_profile');
        }

        return $this->render('account/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function sendEmailAccountConfirmation(MailerInterface $mailer, $userMail, $name, $slug, $token)
    {
        $email = (new TemplatedEmail())
            ->from('juli3n.t3st.d3v@gmail.com')
            ->to($userMail)
            ->subject('Bienvenue sur SnowTricks - confirmez votre adresse mail')
            ->htmlTemplate('emails/registration.html.twig')
            ->context([
                'name' => $name,
                'token' => $token,
                'mail' => $userMail,
                'slug' => $slug,
            ]);

        $mailer->send($email);

    }

    private function updateEmail($user, $manager, $roleRepository, $userRepository, $mailer)
    {
        $roleMember = $roleRepository->findOneByRoleType('ROLE_MEMBER');
        $user = $userRepository->find($user->getId());
        $roleMember->removeUser($user);
        $manager->persist($roleMember);
        $token = $this->generateToken($user->getEmail());
        $user->setToken($token);
        $manager->persist($user);
        $this->sendEmailAccountConfirmation($mailer, $user->getEmail(), $user->getFirstName(), $user->getSlug(), $token);

    }

    private function generateToken($email)
    {
        return md5($email) . md5(mt_rand(0, 9));
    }
}
