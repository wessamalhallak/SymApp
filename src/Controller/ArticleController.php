<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameWorkExtraBundle\Configuration\Method;
use App\Entity\Article;

class ArticleController extends Controller
{
    /**
     * @Route (path="/",name="article_index")
     */
    public function index()
    {
        $article=$this->getDoctrine()->getRepository(Article::class)->findAll();
        #return new Response("<html><body>Hello</bod></html>")    ;

        // $article = ["article1",
        //     "article2",
        //     "article3",
        //     "article4"];

        return $this->render("articles/index.html.twig", array("name" => "Wessam","articles"=>$article));
    }

    /**
     * @Route(path="/article/{id}", name="article_show")
     */
    public function show($id)
    {
        $article=$this->getDoctrine()->getRepository(Article::class)->find($id);
        
        return $this->render("articles/show.html.twig",array("name"=>"Wessam",'article'=>$article));

    }

    /**
     * @Route(path="/article/save",name="article_save")
     */
    public function save ()
    {
        $entityManager=$this->getDoctrine()->getManager();
        $article= new Article;
        $article->setTitle("Article One");
        $article->setBody("This is the body for article One");
        $entityManager->persist($article);
        $entityManager->flush();

        return new Response("saved an article with the id of ".$article->getId());
        
    }
}
