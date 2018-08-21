<?php
namespace App\Controller;
use App\Entity\User;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ArticlesController extends FOSRestController
{
    private $em;
    public function __construct( EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function getArticlesAction(Article $article)
    {
        return $this->view($article);
    }
    public function getArticleAction(int $id)
    {
    }
    public function postArticlesAction(Article $article,Request $request, EntityManagerInterface $em)
    {

        $article->setUser($request->getUser());
        $this->em->persist($article);
        $this->em->flush();
        return new JsonResponse('yes');
    }
    public function putArticleAction(int $id)
    {
    }
    public function deleteArticleAction(int $id)
    {
    }
}