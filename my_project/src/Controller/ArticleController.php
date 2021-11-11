<?php


namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController

{

    /**

     * @Route("/all-articles", name="get_posts")

     */

    public function getPosts(ArticleRepository $article): Response

    {

        //récuperation du numero de la page
        $page = 1;
        $pageSize = 5;
        //calcul de l'index
        $index = ($page - 1) * $pageSize;
        $articles = $article->getArticlesByIndex($index);

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

     * @Route("/article/{id}", name="get_post")

     */

    public function getPost(ArticleRepository $article, $id): Response

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
}
