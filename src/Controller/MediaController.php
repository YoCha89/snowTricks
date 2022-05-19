<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ImgType;
use App\Form\VidType;
use App\Form\MediaUpType;
use App\Entity\Media;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MediaController extends AbstractController
{
    /**
     * @Route("/media/{id}", name="media")
     */
    public function index(): Response
    {
        return $this->render('media/index.html.twig', [
            'controller_name' => 'MediaController',
        ]);
    }

    /**
     * @Route("/manage_media", name="manage_media")
     */
    public function manageMediaAction(Request $request): Response {
        $em = $this->getDoctrine()->getManager();
        $medias = $em->getRepository(Media::class)->findAll();

        $title = 'Medias';

        return $this->render('media/manage_media.html.twig', [
            'medias'=>$medias,
            'title' => $title
        ]);
    }

    /**
     * @Route("/create_video", name="create_video")
     */
    public function createVideoAction(Request $request): Response {
        $media = new Media();

        $title = 'Ajouter une image';
        $form = $this->createForm(VidType::class);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $title = $form['title']->getData().'.jpg';
            $mediaCheck = $em->getRepository(Media::class)->findOneBy(array('title' => $title));

            if($mediaCheck == null){
                
                $path = $form['mediaPath']->getData();
                $trick = $form['trick']->getData();
                
                if (!preg_match('/embed/', $path)) {
                    $raw = preg_split('/\//', $path);
                    $code = end($raw);
                    $path = 'https://www.youtube.com/embed/' . $code;
                }

                $media->setType('vid');
                $media->setTitle($title);
                $media->setTrick($trick);
                $media->setMediaPath($path);

                $em->persist($media);
                $em->flush();

            }else{
                $this->addFlash('error', 'Ce nom d\'image est déja utilisé. Utiliser un nouveau nom de pour cette image.');
            }

            return $this->redirectToRoute('create_video'); 
        }

        return $this->render('media/create_media.html.twig', [
            'form' => $form->createView(),
            'media' => $media,
            'title' => $title
        ]);
    }

    /**
     * @Route("/create_image", name="create_image")
     */
    public function createImageAction(Request $request): Response {
        $media = new Media();

        $title = 'Ajouter une image';
        $form = $this->createForm(ImgType::class);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $title = $form['title']->getData();
            $titlePath = $title.'.jpg';
            $mediaCheck = $em->getRepository(Media::class)->findOneBy(array('title' => $title));

            if($mediaCheck == null){
                $trick = $form['trick']->getData();

                $directory = $this->getParameter('upload_dir_trick');
                $path = $directory.'/'.$titlePath;
                $file = $form['mediaPath']->getData();
                $file->move($directory, $titlePath);
                $pathRaw = explode('images', $path); 
                $path = 'images'.$pathRaw[1];
                

                $media->setTitle($title);
                $media->setTrick($trick);
                $media->setMediaPath($path);

                $imgPCheck = $em->getRepository(Media::class)->findOneBy(array('trick'=>$trick, 'type'=>'imgP'));

                if($imgPCheck == null){
                    $media->setType('imgP');
                }else{
                    $media->setType('img');
                }

                $em->persist($media);
                $em->flush();

            }else{
                $this->addFlash('error', 'Ce nom d\'image est déja utilisé. Utiliser un nouveau nom de pour cette image.');
            }

            return $this->redirectToRoute('create_image');
        }

        return $this->render('media/create_media.html.twig', [
            'form' => $form->createView(),
            'media' => $media,
            'title' => $title
        ]);
    }

    /**
        * @Route("/update_media/{id}", name="update_media")
        */
    public function updateMediaAction(Request $request, Media $media): Response {

        $form = $this->createForm(MediaUpType::class, $media);
        $form->handleRequest($request);

        $imgp = $media->getType();
        if($media->getType() == 'imgP'){
            $imgp = true;
        }else{
            $medias = $media->getTrick()->getMedias();
            foreach($media as $med){
                if($med->getType() == 'imgP'){
                    $imgP = $med->getTitle();
                    break;
                }
            }            
        }


        if ($form->isSubmitted() && $form->isValid())
            {
                $em = $this->getDoctrine()->getManager();

                $title = $form['title']->getData();

                $media->setTitle($title);

                $em->persist($media);
                $em->flush();

                return $this->redirectToRoute('manage_media');
            }

        $title = 'Modifer un media';
        $update = 'update';

        return $this->render('media/update_media.html.twig', [
            'form' => $form->createView(),
            'media' => $media,
            'title' => $title,
            'update' => $update,
            'imgp' => $imgp
        ]);
    }

    /**
        * @Route("/delete_media/{id}", name="delete_media")
        */
    public function deleteMediaAction(Request $request, Media $media): Response {

        $em = $this->getDoctrine()->getManager();
        $em->remove($media);
        $em->flush();
        
        return $this->redirectToRoute('manage_media');

    }


    /**
        * @Route("/display_media/{id}", name="display_media")
        */
    public function displayMediaAction(Request $request, Media $media): Response {

        $title = $media->getTitle();

        return $this->render('media/display_media.html.twig', [
            'media' => $media,
            'title' => $title,
        ]);
    }

}
