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
            $secretQ = $form['secretQ']->getData();
            $secretA = $form['secretA']->getData();
        }

        return $this->render('account/create_account.html.twig', [
            'form' => $form->createView(),
            'account' => $account
        ]);
    }
}
