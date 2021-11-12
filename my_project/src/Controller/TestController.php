<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function createArticle(Request $request): Response
    {
        $data = $request->request->all();

        if (isset($data['title']) && isset($data['contenu']) && isset($data['auteur'])) {

            $article = new Article();

            $article->setTitre($data['title']);

            $article->setContenu($data['contenu']);

            $article->setAuteur($data['auteur']);

            $date = new \DateTime();

            $article->setDatepublication($date);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return new Response('success with id: ' . $article->getId());
        } else {

            return new Response('error');
        }
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
     * @Rest\Delete("/{article}")
     */
    public function deleteArticle(Article $article)
    {

        if (empty($article)) {
            throw $this->createNotFoundException('404 not found');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();
        return "deleted";
    }

    /**
     * @Rest\Put("/article-updated/{id}")
     */
    public function modifyArticle(ArticleRepository $article1, $id, Request $request)
    {

        $article = $article1->find($id);

        $data = $request->request->all();

        if ($article) {

            if (isset($data['title']) && isset($data['contenu']) && isset($data['auteur'])) {

                $article->setTitre($data['title']);

                $article->setContenu($data['contenu']);

                $article->setAuteur($data['auteur']);

                $date = new \DateTime();

                $article->setDatepublication($date);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->merge($article);
                $entityManager->flush();

                return new Response('updated with id: ' . $article->getId());
            } else {

                return new Response('error');
            }
        } else {

            if (isset($data['title']) && isset($data['contenu']) && isset($data['auteur'])) {

                $article = new Article();

                $article->setTitre($data['title']);

                $article->setContenu($data['contenu']);

                $article->setAuteur($data['auteur']);

                $date = new \DateTime();

                $article->setDatepublication($date);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($article);
                $entityManager->flush();
                return new Response('updated with id: ' . $article->getId());
            }
        }
    }
}
