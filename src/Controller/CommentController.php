<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommentType;
use App\Entity\Comment;

class CommentController extends AbstractController
{

    /**
     * @Route("/update_comment/{id}", name="update_comment")
     */
    public function updateCommentAction(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $slug = $comment->getTrick()->getSlug();

            $comment->setUpdatedAt(new \DateTime());

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('show_trick', ['slug' => $slug]); 
        }

        $title = 'Mettre à jour votre commentaire';

        return $this->render('comment/update_comment.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment,
            'title' => $title
        ]);
  }

   /**
     * @Route("/delete_comment/{id}", name="delete_comment")
     */
    public function deleteCommentAction(Request $request, Comment $comment): Response
    {   
        $em = $this->getDoctrine()->getManager();

        if($request->get('checkDone') != null){
            $slug = $comment->getTrick()->getSlug();

            $lvl = $comment->getLvl();
            $comments = $comment->getComments();
            $tabComments = [];
            $tabComments[$lvl] = array($comment);

            if(count($comments) != 0){
                $tabComments = $this->prepareDeletionComments($comments, $lvl, $tabComments);
            }else{
                $tabComments[$lvl] = $comment;
            }

            foreach($tabComments as $tab){
                $em->remove($tab);
            }

            $em->flush();

            return $this->redirectToRoute('show_trick', ['slug' => $slug]);            
        }else{
            $title = 'Confirmation de suppression';
            return $this->render('comment/deletion_check.html.twig', [
                'comment' => $comment,
                'title' => $title
            ]);  
        }

    }

    
    //Prepare the comments  and replies to be deleted with heritage concerns. Avoid constraints violation by cancelling their interconnected relationships through a recursive function. 
    protected function prepareDeletionComments($comments, $lvl, $tabComments){

        $em = $this->getDoctrine()->getManager();
        $lvl = $lvl+1;

        $tmp=[];

        if(!isset($tabComments[$lvl])){
            foreach($comments as $com){
                array_push($tmp, $com);
            }
            $tabComments[$lvl] = $tmp;
        }else{
            foreach($comments as $com){
                array_push($tmp, $com);
            }
            $tabComments[$lvl] = array_merge($tabComments[$lvl], $tmp);
        }

        $nextBatch = [];
        foreach ($comments as $com){
            
            $check = count($com->getComments());
            if ($check != 0) {
                foreach($com->getComments() as $next){
                    array_push($nextBatch, $next);
                }
            }
        }

        if(!empty($nextBatch)){
            return $this->prepareDeletionComments($nextBatch, $lvl, $tabComments);
        }        

        $finalLvl = count($tabComments) - 1;

        while($finalLvl >= 1){
            if(!isset($tabComments[$finalLvl])){
                dd($finalLvl);
            }
            foreach($tabComments[$finalLvl] as $delC){
                $comChild = $delC->getComments();
                foreach($comChild as $child){
                    $delC->removeComment($child);
                    $em->persist($delC);
                    $em->flush();
                }
            }

            $finalLvl = $finalLvl - 1;
        }

        $tabFinal = [];

        foreach($tabComments as $tab){
            foreach($tab as $tmp){
                array_push($tabFinal, $tmp);
            }
        }

        return $tabFinal;
    }
}
