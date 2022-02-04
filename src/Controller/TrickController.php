<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TrickType;
use App\Entity\Trick;

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

    /**
     * @Route("/create_trick", name="create_trick")
     */
    public function createTrickAction(Request $request): Response {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $name = $form['name']->getData();
            $content = $form['content']->getData();
        }

        return $this->render('trick/create_trick.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick
        ]);
    }
}
