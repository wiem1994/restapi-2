<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\AbstractFOSRestController;



class TestController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/articles-new")
     */
    public function getArticles(ArticleRepository $article)
    {

        //récuperation du numero de la page
        // $page = 1;
        // $pageSize = 5;
        //calcul de l'index
        // $index = ($page - 1) * $pageSize;
        // $articles = $article->getArticlesByIndex($index);

        $articles = $article->findAll();

        $data = array();
        foreach ($articles as $key => $article) {

            $data[$key]['id'] = $article->getId();
            $data[$key]['titre'] = $article->getTitre();
            $data[$key]['contenu'] = $article->getContenu();
            $data[$key]['auteur'] = $article->getAuteur();
            $data[$key]['datepublication'] = $article->getDatepublication();
        }

        return new JsonResponse($data);
    }

    /**
     * @Rest\Get("/article-new/{id}")
     */
    public function getArticle(ArticleRepository $article, $id)
    {
        $articles = $article->find($id);
        $array[] = $articles;
        if (empty($articles)) {
            throw $this->createNotFoundException('404 not found');
        }
        $formatted = [
            'id' => $articles->getId(),
            'titre' => $articles->getTitre(),
            'contenu' => $articles->getContenu(),
            'auteur' => $articles->getAuteur(),
            'datepublication' => $articles->getDatepublication(),
        ];
        return new JsonResponse(json_encode($formatted));
    }


    /**
     * @Rest\Post("/articles")
     */
    public function createArticle(Request $request)
    {

        $article = new Article;
        $article->setTitre("test")
            ->setContenu("test")
            ->setAuteur("test")
            ->setDatepublication(new DateTime());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($article);
        $entityManager->flush();


        return "ok";
    }

    /**
     * @Rest\Get("/three-articles")
     */
    public function getThreeArticles(ArticleRepository $article)
    {


        $articles = $article->findAll();
        $data = array();
        foreach ($articles as $key => $article) {

            $data[$key]['id'] = $article->getId();
            $data[$key]['titre'] = $article->getTitre();
            $data[$key]['contenu'] = $article->getContenu();
            $data[$key]['auteur'] = $article->getAuteur();
            $data[$key]['datepublication'] = $article->getDatepublication();
        }
        $array = array_slice($data, -3);

        return new JsonResponse($array);
    }

    /**
     * @Rest\Delete("/delete-article/{id}")
     */
    public function deleteArticle(ArticleRepository $article, $id)
    {
        $oneArticle = $article->find($id);
        if (empty($oneArticle)) {
            throw $this->createNotFoundException('404 not found');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($oneArticle);
        $entityManager->flush();
        return "deleted";
    }

    /**
     * @Rest\Put("/article/{id}")
     */
    public function modifyArticle(ArticleRepository $article, $id)
    {
        $oneArticle = $article->find($id);
        if (!empty($oneArticle)) {
            $oneArticle->setTitre("updated");
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($oneArticle);
            $entityManager->flush();
            return "updated";
        } else {
            $article = new Article;
            $article->setTitre("test")
                ->setContenu("test")
                ->setAuteur("test")
                ->setDatepublication(new DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            return "created";
        }
    }
}
