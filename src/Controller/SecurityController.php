<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LoginType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function index(): Response
    {
        $form = $this->createForm(LoginType::class);     

        return $this->render('security/login.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(){

    }
}
