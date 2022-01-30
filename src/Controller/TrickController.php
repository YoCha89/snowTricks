<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @Route("/trick_list", name="trick_list")
     */
    public function trickListAction(): Response
    {
        return $this->render('trick/trick_list.html.twig', [
            'controller_name' => 'TrickController',
        ]);
    }
}
