<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AccountType;
use App\Entity\Account;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    /**
     * @Route("/create_account", name="create_account")
     */
    public function createAccountAction(Request $request): Response {
        $account = new Account();
        $form = $this->createForm(AccountType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $email = $form['email']->getData();
            $fullName = $form['fullName']->getData();
            $pass = $form['password']->getData();
            $profilePic = $form['profilePic']->getData();
            dd($profilePic);
            $check = $em->getRepository(Account::class)->findBy(array('email'=>$email));

             if($check == null){
                $account->setEmail($name);
                $account->setFullName($content);
                $account->setPass($pass);

                
                $em->persist($account);
                $em->flush();

                return $this->redirect($this->generateUrl('create_account')); 
                
            }else{
                $this->addFlash('error', 'Ce nom de figure est déja utilisé. Utiliser un nouveau nom de figure ou <a href="{{ path(\'update_trick\') }}">mettez à jour la figure portant ce nom</a>.');
            }
        }

        return $this->render('account/create_account.html.twig', [
            'form' => $form->createView(),
            'account' => $account
        ]);
    }
}
