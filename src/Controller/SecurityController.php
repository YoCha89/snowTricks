<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use App\Entity\Account;
use App\Security\AppAuthenticator;

class SecurityController extends AbstractController
{   
    // Entry point for connecting the user
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

    //checks if a user has verified his email when registering before connecting him
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


    //concludes the register action by setting isVerified to true when the user email has been confirmed
    /**
     * @Route("/verify_user", name="verify_user")
     */
    public function verifyUser(UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, Request $request) {
        $fullName = $request->get('fullName');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Account::class)->findOneBy(array('fullName' => $fullName));
        $user->setIsVerified(true);

        $em->persist($user);
        $em->flush();

        return $userAuthenticator->authenticateUser(
            $user,
            $authenticator,
            $request
        );

        return new RedirectResponse('/');
    }

    /**
     * @Route("/new_pass", name="new_pass")
     */
    public function newPassAction(Request $request) {

        $form = $this->createFormBuilder()
            ->add('fullName')
            ->getForm();

        $form->handleRequest();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $fullName = $form->get('fullName');
            $user = $em->getRepository(Account::class)->findOneBy(array('fullName'=>$fullName));

            if($user != null){
                $email = $user->getEmail();

                $this->newPassEmail($email);
                $this->addFlash('success', 'Un lien pour réinitialiser votre mot de passe vous a été envoyé sur l\'adresse mail de votre compote');
                return $this->redirect($this->generateUrl('/')); 
            }else{
                $this->addFlash('error', 'Aucun utilisateur inscrit sous ce nom');  
                return $this->redirect($this->generateUrl('/')); 
            }
        }

        $title = 'Demander un nouveau mot de passe';

        return $this->render('security/new_pass.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
        ]);
    }

    protected function resetPass(){

        $form = $this->createFormBuilder()
            ->add('fullName')
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->getForm();

        $form->handleRequest();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $fullName = $form->get('fullName');
            $plainPassword = $form->get('plainPassword');


            $user = $em->getRepository(Account::class)->findOneBy(array('fullName'=>$fullName));

            if($user != null){
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $em->persist($user);
                $em->flush();    

                $this->addFlash('success', 'Votre mot de pass a été mis à jour.');
                return $this->redirect($this->generateUrl('/')); 
            }else{
                $this->addFlash('error', 'Aucun utilisateur inscrit sous ce nom.');  
                return $this->redirect($this->generateUrl('/')); 
            }

  
        }

        $title = 'Réinitialisez votre mot de passe';

        return $this->render('security/reset_pass.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    //used to send mail to the user
    protected function newPassEmail(MailerInterface $mailer, $email){

        $mailToSend = (new TemplatedEmail())
            ->from(new Address('yoachar89@gmail.com', 'systemMail'))
            ->to($email)
            ->subject('Confirmation Email')
            ->htmlTemplate('registration/ask_pass_email.html.twig');

        $mailer->send($mailToSend);
    }
}

