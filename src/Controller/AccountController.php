<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AccountType;
use App\Entity\Account;
use App\Entity\Comment;

class AccountController extends AbstractController
{
    /**
     * @Route("/tmp", name="tmp")
     */
    public function tmp(): Response
    { 
        
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Account::class)->findOneBy(array('email'=>'ychardel@gmail.com'));

        $user->setRoles(['ROLE_ADMIN']);


        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('create_trick')); 
    }


    /**
     * @Route("/update_account", name="update_account")
     */
    public function updateAccountAction(Request $request): Response {

        return $this->redirect($this->generateUrl('index')); 
    }

    /**
     * @Route("/delete_account", name="delete_account")
     */
    public function deleteAccountAction(Request $request): Response {
        
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $userComments = $em->getRepository(Comment::class)->findBy(array('account' => $user));

        foreach($userComments as $comment){
            $comments = $comment->getComments();/*
            $lvl = $comment->getLvl();
            $tabComments = [];
            $tabComments[$lvl] = array($comment);

            $tabFinal = $this->forward('App\Controller\CommentController::prepareDeletionComments', [
                'comments'  => $comments,
                'lvl' => $lvl,
                'tabComments' => $tabComments,
            ]);*/

            $tabComments = $this->prepareCommentsByThreads($comments, $user);

    /*        foreach($tabComments as $tab){
                $em->remove($tab);
            }*/

            $em->flush();
        }

        return $this->redirect($this->generateUrl('index')); 
    }

    protected function prepareCommentsByThreads($comments, $user){
        $commentsId = [];
        $lvlMax = 1;

        foreach($comments as $comment){
            $commentsId[$comment->getId()] = $comment;

            if($comment->getLvl()>$lvlMax){
                $lvlMax = $comment->getLvl();
            }
        }

        /*for ($i=$lvlMax; $i<=1; $i--){
            foreach($comments as $com){
                if($com->getLvl() == $i){
                    $comParent = $com->getCommentParent();
                    while($comParent != null){
                        
                        if($comParent->getAccount() == $user){
                            unset()
                        }
                    }
                }
            }
        }*/

    }
}
