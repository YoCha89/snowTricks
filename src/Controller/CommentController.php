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
       return $this->redirectToRoute('/');
    }

    /**
     * @Route("/delete_comment/{id}", name="delete_comment")
     */
    public function deleteCommentAction(Request $request, Comment $comment): Response
    {   
        $id = $comment->getTrick()->getId();
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();
        return $this->redirectToRoute('show_trick', ['id' => $id]);
    }

}
