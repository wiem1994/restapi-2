<?php


namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticlesController extends AbstractController

{
    /**

     * @Route("/list-articles", name="get_posts_list")

     */

    public function getPosts(ArticleRepository $article)

    {
        return $this->render('Web/articles.html.twig');
    }
}
