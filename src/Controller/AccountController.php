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
     * @Route("/delete_account", name="delete_account")
     */
    public function deleteAccountAction(Request $request): Response {
        
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if($request->get('checkDone') != null){
            $userComments = $em->getRepository(Comment::class)->findBy(array('account' => $user));

            if(count($userComments) != 0){
                foreach($userComments as $comment){
                    $lvl = $comment->getLvl();

                    if(!isset($lvlMax)){
                        $lvlMax = $lvl;
                    }else{
                        if($lvl > $lvlMax){
                            $lvlMax = $lvl;
                        }
                    }
                }

                $finalLvl = 1;

                while($finalLvl <= $lvlMax){

                    foreach($userComments as $comment){
                        if($comment->getLvl() == $lvlMax){
                            $this->deleteComment($comment);
                        }
                    }

                    $lvlMax--;
                }
            }

            $this->container->get('security.token_storage')->setToken(null);
            
            $em->remove($user);
            $em->flush();

            // $request->getSession()->invalidate();
            $this->addFlash('success', 'Votre compte utilisateur a bien été supprimé !');
            return $this->redirectToRoute('index');             
        }else{
            $title = 'Confirmation de suppression';
            return $this->render('account/deletion_check.html.twig', [
                'title' => $title
            ]);  
        }
    }


    //delete comments for deleteAccountAction, receiving them in decreasing order of lvl to avoid constraints violation if the user had multiple replies within the same thread.
    protected function deleteComment(Comment $comment) {   
        $em = $this->getDoctrine()->getManager();

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
    }

    
    //Prepare the comments  and replies to be deleted with heritage concerns by deleteComment. Avoid constraints violation by cancelling their interconnected relationships through a recursive function. 
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

        foreach($tabComments as $key => $tab){
            $lvl = $key;

            if(!isset($lvlMin)){
                $lvlMin = $key;
            }else{
                if($key < $lvlMin){
                    $lvlMin = $key;
                }
            }

            if(!isset($lvlMax)){
                $lvlMax = $key;
            }else{
                if($key > $lvlMax){
                    $lvlMax = $key;
                }
            }
        }

        while($lvlMax >= $lvlMin){
            // if(!in_array(1, $tabComments) && $finalLvl == 1){dd($tabComments);}
            foreach($tabComments[$lvlMax] as $delC){
                $comChild = $delC->getComments();
                foreach($comChild as $child){
                    $delC->removeComment($child);
                    $em->persist($delC);
                    $em->flush();
                }
            }

            $lvlMax = $lvlMax - 1;
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
