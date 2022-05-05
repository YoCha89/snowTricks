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
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{   
    // Entry point for connecting the user
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
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
    public function newPassAction(Request $request, MailerInterface $mailer) {

        $form = $this->createFormBuilder()
            ->add('fullName')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $fullName = $form->get('fullName')->getData();
            $user = $em->getRepository(Account::class)->findOneBy(array('fullName'=>$fullName));

            if($user != null){
                $email = $user->getEmail();

                $this->newPassEmail($mailer, $email, $fullName);
                $this->addFlash('success', 'Un lien pour réinitialiser votre mot de passe vous a été envoyé sur l\'adresse mail de votre compte');
                return $this->redirect($this->generateUrl('index')); 
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

    /**
     * @Route("/reset_pass", name="reset_pass")
     */
    public function resetPass(Request $request, UserPasswordHasherInterface $userPasswordHasher){

        $form = $this->createFormBuilder()
            ->add('fullName')
            ->add('plainPassword1', PasswordType::class, [
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
            ->add('plainPassword2', PasswordType::class, [
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

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $fullName = $form->get('fullName')->getData();
            $plainPassword1 = $form->get('plainPassword1')->getData();
            $plainPassword2 = $form->get('plainPassword2')->getData();

            if($plainPassword2 == $plainPassword1){
                $user = $em->getRepository(Account::class)->findOneBy(array('fullName'=>$fullName));

                if($user != null){
                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword1')->getData()
                        )
                    );

                    $em->persist($user);
                    $em->flush();    

                    $this->addFlash('success', 'Votre mot de pass a été mis à jour.');
                    return $this->redirect($this->generateUrl('index')); 
                }else{
                    $this->addFlash('error', 'Aucun utilisateur inscrit sous ce nom.');  
                    return $this->redirect($this->generateUrl('index')); 
                }
            }else{
                $this->addFlash('error', 'Vous avez utilisé 2 mots de passe différents.');  
                return $this->redirect($this->generateUrl('reset_pass')); 
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
    protected function newPassEmail($mailer, $email, $fullName){

        $mailToSend = (new TemplatedEmail())
            ->from(new Address($this->getParameter('app.mailadmin'), 'systemMail'))
            ->to($email)
            ->subject('Confirmation Email')
            ->htmlTemplate('security/ask_pass_email.html.twig')
            ->context([
                'fullName' => $fullName,
            ]);;

        $mailer->send($mailToSend);
    }
}

