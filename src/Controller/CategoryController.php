<?php

namespace App\Controller;
                     
use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\Thread;
use App\Entity\Account;
use App\Entity\Comment;                                                                                  
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{

    /**
     * @Route("/create_category", name="create_category", methods={"GET", "POST"})
     */
    public function create_category(Request $request, EntityManagerInterface $entityManager): Response {
        /*-------------------------------------COMMENT FIX------------------------------------*/

        $em = $this->getDoctrine()->getManager();
        $tricks = $em->getRepository(Trick::class)->findAll();
        $accounts = $em->getRepository(Account::class)->findAll();

        foreach($tricks as $t){
            for($i=0; $i<=5; $i++){
                $ind = array_rand($accounts, 1);
                $account = $accounts[$ind];

                $thread = new thread();

                $tmp = new comment();
                $tmp->setThread($thread);
                $tmp->setTrick($t);
                $tmp->setLvl(1);
                $tmp->setCreatedAt(new \DateTime());
                $tmp->setAccount($account);
                $tmp->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur feugiat quam a vehicula efficitur. Morbi mattis pretium consectetur. In sed lacinia leo. Pellentesque lobortis placerat risus, quis pretium turpis sollicitudin non.');
                if($i == 2 || $i == 4){
                    for($i=0; $i<=5; $i++){
                        $ind = array_rand($accounts, 1);
                        $account = $accounts[$ind];

                        $tmp2 = new comment();
                        $tmp2->setTrick($t);
                        $tmp2->setLvl(2);
                        $tmp2->setThread($thread);
                        $tmp2->setCommentParent($tmp);
                        $tmp2->setCreatedAt(new \DateTime());
                        $tmp2->setAccount($account);
                        $tmp2->setContent('Morbi mattis pretium consectetur. In sed lacinia leo. Pellentesque lobortis placerat risus, quis pretium turpis sollicitudin non. Mauris id lacinia nibh, non feugiat nunc. Nam sit amet metus mollis, ullamcorper augue vitae, tristique ipsum. Nunc scelerisque eros at interdum dignissim. Fusce pellentesque tellus accumsan est egestas, id blandit dui ultricies. Sed lacinia enim felis, quis hendrerit felis semper a.');
                        if($i == 3 || $i == 7){
                            $ind = array_rand($accounts, 1);
                            $account = $accounts[$ind];

                            $tmp3 = new comment();
                            $tmp3->setTrick($t);
                            $tmp3->setLvl(3);
                            $tmp3->setThread($thread);
                            $tmp3->setCommentParent($tmp2);
                            $tmp3->setCreatedAt(new \DateTime());
                            $tmp3->setAccount($account);
                            $tmp3->setContent('Nunc scelerisque eros at interdum dignissim. Fusce pellentesque tellus accumsan est egestas, id blandit dui ultricies. Sed lacinia enim felis, quis hendrerit felis semper a. Curabitur ultricies, sapien id semper accumsan, urna magna varius tortor, vehicula faucibus justo massa in libero.');
                            $em->persist($tmp3);
                        }

                        $em->persist($tmp2);
                    }
                }

                $em->persist($tmp);
            }            
        }
        $em->flush();
        return $this->redirectToRoute('index');
        /*-------------------------------------TRICK FIX------------------------------------*/
/*        $em = $this->getDoctrine()->getManager();

        $str = 'azertyuiopqsdfghjklmwxcvbn0123456789';
        $cat = array(0,1,2,3,4,5,6);

        for($i=0; $i<=40; $i++){
            $ind = array_rand($cat, 1);
            $cate = $cat[$ind];

            $category = $em->getRepository(Category::class)->findOneBy(array('id' => $cate));
            $schufS = str_shuffle($str);

            $name =substr($schufS, 0, 6);
            $slug= 'snow_trick_'. $name;

            $tmp = new Trick();
            $tmp->setCategory($category);
            $tmp->setName($name);
            $tmp->setSlug($slug);
            $tmp->setCreatedAt(new \DateTime());
            $tmp->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur feugiat quam a vehicula efficitur. Morbi mattis pretium consectetur. In sed lacinia leo. Pellentesque lobortis placerat risus, quis pretium turpis sollicitudin non. Mauris id lacinia nibh, non feugiat nunc. Nam sit amet metus mollis, ullamcorper augue vitae, tristique ipsum. Nunc scelerisque eros at interdum dignissim. Fusce pellentesque tellus accumsan est egestas, id blandit dui ultricies. Sed lacinia enim felis, quis hendrerit felis semper a. Curabitur ultricies, sapien id semper accumsan, urna magna varius tortor, vehicula faucibus justo massa in libero.');

            $em->persist($tmp);
        }
        $em->flush();

        return $this->redirectToRoute('index');*/ 


        /*-------------------------------Cate------------------------------------------*/  
   /*     $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('create_category', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category/create_category.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);*/
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"POST"})
     */
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index', [], Response::HTTP_SEE_OTHER);
    }
}
