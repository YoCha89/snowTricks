<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $user = new Account();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $check = $em->getRepository(Account::class)->findBy(array('fullName'=>$form->get('fullName')->getData()));

            if($check == null){

                // encode the plain password
                $user->setPassword(
                $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $directory = 'images\profile_pic';
                $picTitle = str_replace(' ', '_', $user->getFullName()).'.jpg';
                $path = $directory.'/'.$picTitle;
                $file = $form['profilePic']->getData();
                $file->move($directory, $picTitle);

                $user->setProfilePic($path);
                $user->setIsVerified(false);

                $entityManager->persist($user);
                $entityManager->flush();

                // generate a signed url and email it to the user
                $fullName = $user->getFullName();
                $email = $user->getEmail();

                $this->verifyEmail($mailer, $email, $fullName);
                $this->addFlash('success', 'Un lien pour réinitialiser votre mot de passe vous a été envoyé sur l\'adresse mail de votre compote');

                return $this->redirect($this->generateUrl('index')); 

            }else{
                $this->addFlash('error', 'Ce nom d\'utilisateur est déja utilisé. Utiliser un autre pseudo');
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    //used to send confirmation mail to the user
    protected function verifyEmail($mailer, $email, $fullName){

        $mailToSend = (new TemplatedEmail())
            ->from(new Address('yoachar89@gmail.com', 'systemMail'))
            ->to($email)
            ->subject('Confirmation Email')
            ->htmlTemplate('registration/confirmation_email.html.twig')
            ->context([
                'fullName' => $fullName,
            ]);

        $mailer->send($mailToSend);
    }
}
