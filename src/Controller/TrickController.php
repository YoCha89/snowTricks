<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TrickType;
use App\Entity\Trick;
use Symfony\Component\HttpFoundation\Request;

class TrickController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $tricks = $em->getRepository(Trick::class)->findAll();

        return $this->render('trick/index.html.twig', [
            'controller_name' => 'TrickController',
            'tricks'=>$tricks
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
            $em = $this->getDoctrine()->getManager();

            $name = $form['name']->getData();
            $content = $form['content']->getData();
            $slug = $form['slug']->getData();

            $check = $em->getRepository(Trick::class)->findBy(array('name'=>$name));

            if($check == null){
                $trick->setName($name);
                $trick->setContent($content);
                $trick->setSlug($slug);

                
                $em->persist($trick);
                $em->flush();

            }else{
                $this->addFlash('error', 'Ce nom de figure est déja utilisé. Utiliser un nouveau nom de figure ou <a href="{{ path(\'update_trick\') }}">mettez à jour la figure portant ce nom</a>.');
            }

        }

        return $this->render('trick/create_trick.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/update_trick", name="update_trick")
     */
    public function updateTrickAction(Request $request): Response {

    }
}
