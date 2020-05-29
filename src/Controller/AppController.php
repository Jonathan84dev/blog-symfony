<?php 

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // AbstractController déjà inclus dans Symfony
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // taper Route pour avoir l'autocompletion la 1ere fois

class AppController extends AbstractController {
    public function about()
    {
        return new Response('<h1>Page "A propos"</h1>');

    }

    /**
     * @Route("/", name="app_index", methods={"GET"})
     */

    public function index(ArticleRepository $articleRepository){

    $articles = $articleRepository->findLastArticles(2);

    return $this->render('/App/index.html.twig',[
            'articles' => $articles
            ]);
    }

}