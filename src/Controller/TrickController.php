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
    public function index(Request $request, $origin = null): Response
    {
        $em = $this->getDoctrine()->getManager();
        $tricks = $em->getRepository(Trick::class)->findByOrder();
        
        $totalPage = floor(count($tricks)/20);

        if(null != $request->get('page')){
            $oldPage = $request->get('page');

            if($request->get('turn') == 'next'){
                $page = $oldPage + 1;
            }elseif($request->get('turn') == 'previous'){
                $page = $oldPage-1;
            }
            
            $offset = $page*20;
            $tricksToUse = array_slice($tricks, $offset, 20);
        }else{
            $tricksToUse = array_slice($tricks, 0, 20);
            $page = 1;
        }

        $title = 'Bienvenue sur SnowTricks';

        return $this->render('trick/index.html.twig', [
            'controller_name' => 'TrickController',
            'tricks'=>$tricksToUse,
            'title' => $title,
            'totalPage' => $totalPage,
            'page'=>$page
        ]);
    }

    /**
     * @Route("/create_trick", name="create_trick")
     */
    public function createTrickAction(Request $request): Response {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $check = $em->getRepository(Trick::class)->findBy(array('name'=>$trick->getName()));

            if($check == null){

                $slug = 'snow_trick_'.$trick->getName();
                $trick->setSlug($slug);
                $trick->setCreatedAt(new \DateTime());

                $em->persist($trick);
                $em->flush();

                return $this->redirect($this->generateUrl('index')); 
                
            }else{
                $this->addFlash('error', 'Ce nom de figure est déja utilisé. Utiliser un nouveau nom de figure.');
            }

        }

        $titleForm = 'Ajouter une figure';

        return $this->render('trick/create_trick.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
            'titleForm' =>$titleForm,
        ]);
    }

    /**
     * @Route("/update_trick/{slug}", name="update_trick")
     */
    public function updateTrickAction(Request $request, Trick $trick): Response {

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $check = $em->getRepository(Trick::class)->findBy(array('name'=>$trick->getName()));

            if($check == null){

                $slug = 'snow_trick_'.$trick->getName();
                $trick->setSlug($slug);
                $trick->setUpdatedAt(new \DateTime());

                $em->persist($trick);
                $em->flush();

                return $this->redirect($this->generateUrl('index')); 
                
            }else{
                $this->addFlash('error', 'Ce nom de figure est déja utilisé. Utiliser un nouveau nom de figure.');
            }

        }

        $titleForm = 'Modifier une figure';

        return $this->render('trick/update_trick.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
            'titleForm' =>$titleForm,
        ]);
    }

    /**
     * @Route("/show_trick/{slug}", name="show_trick")
     */
    public function showTrickAction(Request $request, Trick $trick): Response{

        $em = $this->getDoctrine()->getManager();

        $mediasRaw = $trick->getMedias();
        $medias = [];

        foreach($mediasRaw as $med){
            array_push($medias, $med);
        }

        $totalPageMed = floor(count($medias)/5);
// dd($request->get('pageMed'));
        if(null != $request->get('pageMed')){
            $oldPageMed = $request->get('pageMed');

            if($request->get('turn') == 'next'){
                $pageMed = $oldPageMed + 1;
            }elseif($request->get('turn') == 'previous'){
                $pageMed = $oldPageMed-1;
            }
            
            $offset = $pageMed*5;
            $mediasToUse = array_slice($medias, $offset, 5);
        }else{
            $mediasToUse = array_slice($medias, 0, 5);
            $pageMed = 1;
        }

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

            $author = $this->getUser();

            $comment->setAccount($author);
            $comment->setCreatedAt(new \DateTime());

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('show_trick', ['slug' => $trick->getSlug()]); 
        }

        if($reply == false){
            $comments = $em->getRepository(Comment::class)->findByOrder($trick->getId());

            $totalPage = floor(count($comments)/10);

            if(null != $request->get('page')){
                $oldPageCom = $request->get('page');

                if($request->get('turn') == 'next'){
                    $pageCom = $oldPageCom + 1;
                }elseif($request->get('turn') == 'previous'){
                    $pageCom = $oldPageCom - 1;
                }
                
                $offset = $pageCom*10;
                $commentsToUse = array_slice($comments, $offset, 10);
            }else{
                $commentsToUse = array_slice($comments, 0, 10);
                $pageCom = 1;
            }

            $title = $trick->getName();
            $anonymous = 'images/User.png';

            return $this->render('trick/show_trick.html.twig', [
                'trick' => $trick,
                'form' => $form->createView(),
                // 'formMediaSmart' => $formMediaSmart->createView(),
                'title' => $title,
                'comments' => $commentsToUse,
                'anonymous' => $anonymous,
                'page' => $pageCom,
                'totalPage' => $totalPage,
                'totalPageMed' => $totalPageMed,
                'pageMed' => $pageMed,
                'medias'=>$mediasToUse,
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
                'totalPageMed' => $totalPageMed,
                'pageMed' => $pageMed,
                'medias'=>$mediasToUse,
            ]);  
        }
    }

       /**
     * @Route("/delete_trick/{slug}", name="delete_trick")
     */
    public function deleteTrickAction(Request $request, Trick $trick): Response{
        $em = $this->getDoctrine()->getManager();

        if($request->get('checkDone') != null){
            $comments = $trick->getComments();

            foreach($comments as $com){
                $comChild = $com->getComments();
                foreach($comChild as $child){
                    $com->removeComment($child);
                    $em->persist($com);
                    $em->flush();
                }
            }

            foreach($comments as $com){
                $em->remove($com);    
            }

            $em->remove($trick);
            $em->flush();
            
            return $this->redirectToRoute('index');            
        }else{
            $title = 'Confirmation de suppression';
            return $this->render('trick/deletion_check.html.twig', [
                'trick' => $trick,
                'title' => $title
            ]);  
        }
    }

    /**
     * @Route("/define_thumbnail/{slug}", name="define_thumbnail")
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
}
