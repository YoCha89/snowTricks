<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\MediaType;
use App\Entity\Media;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        $media = new Media();
        $form = $this->createForm(MediaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $em = $this->getDoctrine()->getManager();

            $title = $form['title']->getData();
            $mediaPath = $form['mediaPath']->getData()->getPath();

            $media->setTitle($title);
            $media->setMediaPath($mediaPath);

            $em->persist($media);
            $em->flush();

            return $this->redirect($this->generateUrl('create_media'));
        }

        return $this->render('media/create_media.html.twig', [
            'form' => $form->createView(),
            'media' => $media
        ]);
    }
}
