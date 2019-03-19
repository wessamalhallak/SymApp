<?php

namespace App\Controller;

use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends Controller
{
    /**
     * @Route (path="/",name="article_index")
     */
    public function index()
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->findAll();
        #return new Response("<html><body>Hello</bod></html>")    ;

        // $article = ["article1",
        //     "article2",
        //     "article3",
        //     "article4"];

        return $this->render("articles/index.html.twig", array("name" => "Wessam", "articles" => $article));
    }

    /**
     * @Route(path="/article/{id}", name="article_show",requirements={"id"="\d+"})
     */
    public function show($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        return $this->render("articles/show.html.twig", array("name" => "Wessam", 'article' => $article));

    }

    /**
     * @Route(path="/article/save",name="article_save")
     */
    public function save()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = new Article;
        $article->setTitle("Article One");
        $article->setBody("This is the body for article One");
        $entityManager->persist($article);
        $entityManager->flush();

        return new Response("saved an article with the id of " . $article->getId());

    }
    /**
     * @Route(path="/article/new",name="new_article")
     * @Method ({"GET","POST"})
     */
    function new (Request $request) {

        $article = new Article;

        $form = $this->createFormBuilder($article)

            ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('body', TextareaType::class,
                array('required' => false,
                    'attr' => array('class' => 'form-control'))
            )
            ->add('save', SubmitType::class, array('label' => 'Create', 'attr' => array('value' => 'Submit', 'class' => 'btn btn-primary mt-3')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute("article_show",array("id"=>$article->getId()));

        }

        return $this->render('articles/new.html.twig', array('form' => $form->createView()));

    }
       
    /**
     * @Route(path="/article/delete/{id}",name="del",requirements={"id"="\d+"})
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $respons=new Response();
        
        return $respons;


    }


}
