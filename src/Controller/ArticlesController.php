<?php
namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;


class ArticlesController extends FOSRestController
{
    private $articlesRepository;
    private $em;
    public function __construct(ArticlesRepository $articlesRepository, EntityManagerInterface $em)
    {
        $this->articlesRepository = $articlesRepository;
        $this->em = $em;
    }
    /**
     * @Rest\View(serializerGroups={"article.user"})
     */
    public function getArticlesAction()
    {
        $articles = $this->articlesRepository->findAll();
        return $this->view($articles);
    }
    /**
     * @Rest\View(serializerGroups={"article.user"})
     */
    public function getArticleAction(Article $article)
    {
        return $this->view($article);
    }
    /**
     * @Rest\Post("/articles")
     * @ParamConverter("article", converter="fos_rest.request_body")
     */
    public function postArticlesAction(Article $article)
    {
        $article->setUser($this->getUser());
        $this->em->persist($article);
        $this->em->flush();
        return $this->view($article);
    }
    public function putArticleAction(int $id, Request $request)
    {
        $tl = $request->get('title');
        $dp = $request->get('description');
        $article = $this->articlesRepository->find($id);
        if ( $tl ){
            $article->setTitle($tl);
        }
        if ( $dp ){
            $article->setDescription($dp);
        }
        $this->em->persist($article);
        $this->em->flush();
        return $this->view($article);
    }
    public function deleteArticleAction(Article $article)
    {
        $this->em->remove($article);
        $this->em->flush();
    }
}