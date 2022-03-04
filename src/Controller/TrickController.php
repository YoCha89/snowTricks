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
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $tricks = $em->getRepository(Trick::class)->findAll();

        $snowTricks = [];

        foreach($tricks as $trick){
            $snowTrick = [];
            $snowTrick[0] = $trick->getName();
            $snowTrick[1] = $trick->getId();
            $medias = $trick->getMedias();

            foreach($medias as $img){
                $snowTrick[2] = $img->getMediaPath();

                if(preg_match('/^C:/', $snowTrick[2])){
                    break;
                }

            }

            array_push($snowTricks, $snowTrick);
        }
        
        return $this->render('trick/index.html.twig', [
            'controller_name' => 'TrickController',
            'tricks'=>$snowTricks
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
            $medias = $form['medias']->getData();

            $check = $em->getRepository(Trick::class)->findBy(array('name'=>$name));

            if($check == null){
                $trick->setName($name);
                $trick->setContent($content);
                foreach($medias as $media){
                    $trick->addMedia($media);
                }
                $slug = 'snow_trick_'.$name;
                $trick->setSlug($slug);

                
                $em->persist($trick);
                $em->flush();

                return $this->redirect($this->generateUrl('create_trick')); 
                
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

        $em = $this->getDoctrine()->getManager();

        $trick = $em->getRepository(Trick::class)->findOneBy(array('id'=>$request->get('id')));

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

        }

        return $this->render('trick/update_trick.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick
        ]);
    }

       /**
     * @Route("/show_trick", name="show_trick")
     */
    public function showTrickAction(Request $request): Response{
        return $this->render('trick/show_trick.html.twig', [
        ]);
    }

       /**
     * @Route("/delete_trick", name="delete_trick")
     */
    public function deleteTrickAction(Request $request): Response{
        return $this->redirect($this->generateUrl('/')); 
    }
}
