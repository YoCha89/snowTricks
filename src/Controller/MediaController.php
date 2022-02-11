<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\mMdiaType;
use App\Entity\Media;

class MediaController extends AbstractController
{
    /**
     * @Route("/media", name="media")
     */
    public function index(): Response
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }

    /**
     * @Route("/create_media", name="create_media")
     */
    public function createMediaAction(Request $request): Response {
        $media = new media();
        $form = $this->createForm(MediaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $title = $form['title']->getData();
            $content = $form['content']->getData();
        }

        return $this->render('media/create_media.html.twig', [
            'form' => $form->createView(),
            'media' => $media
        ]);
    }
}
