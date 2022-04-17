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

            $trickId = $comment->getTrick()->getId();

            $comment->setUpdatedAt(new \DateTime());

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('show_trick', ['id' => $trickId]); 
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
        $id = $comment->getTrick()->getId();

        $lvl = $comment->getLvl();
        $comments = $comment->getComments();
        $tabComments = [];
        $tabComments[$lvl] = array($comment);

        if(!empty($comments)){
            $tabComments = $this->prepareDeletionComments($comments, $lvl, $tabComments);
        }

        foreach($tabComments as $tab){
            $em->remove($tab);
        }

        $em->flush();

        return $this->redirectToRoute('show_trick', ['id' => $id]);
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
            if ($check != 0) {var_dump('go there');
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
