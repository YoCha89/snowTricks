<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TrickType;
use App\Form\ThumbnailType;
use App\Form\CommentType;
use App\Entity\Trick;
use App\Entity\Thumb;
use App\Entity\Thumbnail;
use App\Entity\Comment;
use App\Entity\Account;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TrickController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $tricks = $em->getRepository(Trick::class)->findByOrder();

        $title = 'Bienvenue sur SnowTricks';

        return $this->render('trick/index.html.twig', [
            'controller_name' => 'TrickController',
            'tricks'=>$tricks,
            'title' => $title
        ]);
    }

    /**
     * @Route("/create_trick", name="create_trick")
     */
    public function createTrickAction(Request $request): Response {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/define_thumbnail/{id}", name="define_thumbnail")
     */
    public function defineThumbnailAction(Request $request, Trick $trick): Response {

        $mediasRaw = $trick->getMedias();
        $medias = [];

        $mediaP = null;

        $thumbnail = new Thumbnail();

        $i = 0;
        foreach($mediasRaw as $media){
            if($media->getType() == 'imgP'){
               $mediaP = $media;
            }elseif($media->getType() == 'img'){
                $thumb = new thumb();
                $thumb->setName($media->getTitle());
                $thumb->setChoice(false);

                $thumbnail->addThumb( $thumb);

                array_push($medias, $media);
            }
        }

        $form = $this->createForm(ThumbnailType::class, $thumbnail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $choiceraw = $form['thumbs']->getData();
            $choice=[];

            foreach($choiceraw as $cho){
                if($cho->getChoice() == true){
                    array_push($choice, $cho->getName());
                }
            }

            $check = count($choice);

            if($check == 0){
                $this->addFlash('error', 'Veuillez sélectionner une image pour définir le thumbnail.');
            }elseif($check > 1){
                $this->addFlash('error', 'Vous ne pouvez définir plusieurs images en tant que thumbnail de la figure. Veuillez n\'en sélectionner qu\'une.');
            }else{
                $newImgP = $em->getRepository(Media::class)->findOneBy(array('title'=>$choice[0]));
                $old = $trick->getMedias();
                foreach($old as $tmp){
                    if($tmp->getType() == 'imgP'){
                        $oldImgP = $tmp;
                    }
                }
                $oldImg->setType('img');
                $newImgP->setType('imgP');

                $em->persist($oldImg);
                $em->persist($newImgP);

                $em->flush();

                $this->addFlash('error', 'Vous ne pouvez définir plusieurs images en tant que thumbnail de la figure. Veuillez n\'en sélectionner qu\'une.');
            }
        }

        return $this->render('trick/define_thumbnail.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
            'medias' => $medias,
            'mediaP' => $mediaP,
        ]);
    }

    /**
     * @Route("/update_trick/{id}", name="update_trick")
     */
    public function updateTrickAction(Request $request, Trick $trick): Response {

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
/*$type = $form['type']->getData();
            if(preg_match('/.jpg$/', $mediaPath) || preg_match('/.png$/', $mediaPath) ){
                $type = 'im';
            }elseif(preg_match('/^https/', $mediaPath)){
                $type = 'vid';
            }
            $media->setType($type);
        if ($form->isSubmitted() && $form->isValid()){

        }*/

        return $this->render('trick/update_trick.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/show_trick/{id}", name="show_trick")
     */
    public function showTrickAction(Request $request, Trick $trick): Response{

        $em = $this->getDoctrine()->getManager();

        $comment = new Comment();
        
        $comment->setTrick($trick);
        $comment->setLvl(1);
        
        $parent = $request->get('commentParent');
        $reply = false;

        if($parent != null){
            $commentParent = $em->getRepository(Comment::class)->findOneBy(array('id'=>$parent));
            $lvl = $commentParent->getLvl() + 1;
            $comment->setCommentParent($commentParent);
            $comment->setLvl($lvl);
            $reply = true;
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $author = $em->getRepository(account::class)->findOneBy(array('id'=> 1));

            $comment->setAccount($author);
            $comment->setCreatedAt(new \DateTime());

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('show_trick', ['id' => $trick->getId()]); 
        }

        if($reply == false){
            $comments = $em->getRepository(Comment::class)->findByOrder($trick->getId());
            $title = $trick->getName();
            $anonymous = 'images/User.png';

            return $this->render('trick/show_trick.html.twig', [
                'trick' => $trick,
                'form' => $form->createView(),
                'title' => $title,
                'comments' => $comments,
                'anonymous' => $anonymous,
            ]);  
        }else{
            $title = 'Réponse à '. $commentParent->getAccount()->getFullName();
            $anonymous = 'images/User.png';

            return $this->render('comment/reply_comment.html.twig', [
                'trick' => $trick,
                'commentParent' => $commentParent,
                'form' => $form->createView(),
                'title' => $title,
                'anonymous' => $anonymous,
            ]);  
        }
    }
    
/*    protected function replyComment($comment, $commentParent){
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        $comments = $trick->getComments();
        $title = $trick->getName();

        return $this->render('trick/show_trick.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'title' => $title,
            'comments' => $comments
        ]);                   
    }*/

       /**
     * @Route("/delete_trick/{id}", name="delete_trick")
     */
    public function deleteTrickAction(Request $request, Trick $trick): Response{
        $id = $trick->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($trick);
        $em->flush();
        return $this->redirectToRoute('show_trick', ['id' => $id]); 
    }
}
