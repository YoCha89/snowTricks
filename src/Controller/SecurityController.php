<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/checkIsVerified", name="checkIsVerified")
     */
    public function checkIsVerified() {
        if($this->getUser() != null){
            if($this->getUser()->getIsVerified() != false){
                return new RedirectResponse('/');
            }else{
                $this->addFlash('error', 'Veuillez confirmer la création de votre compte en cliquant sur le lien qui vous a été envoyé par email.');
                return new RedirectResponse('/logout');
            }
        }
    }

    /**
     * @Route("/verifyUser", name="verifyUser")
     */
    public function verifyUser($id, UserAuthenticatorInterface $userAuthenticator, Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Account::class)->findOneBy(array('id' => $id));
        $user->setIsVerified(true);

        return $userAuthenticator->authenticateUser(
            $user,
            $authenticator,
            $request
        );

        return new RedirectResponse('/');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
