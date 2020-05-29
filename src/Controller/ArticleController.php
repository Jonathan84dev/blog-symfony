<?php 

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // AbstractController déjà inclus dans Symfony
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route; // taper Route pour avoir l'autocompletion la 1ere fois

/**
 * On préfixe toutes les routes du controller par "/articles"
 * @Route("/articles")
 */
class ArticleController extends AbstractController {    
    
    /**
    * Afficher tous les articles
    * 
    * @Route("/", name="article_index", methods={"GET"})
    */

   public function index() {
    $articleRepository = $this->getDoctrine()->getRepository(Article::class);

    $articles = $articleRepository->findAll();

    return $this->render('/Articles/index.html.twig',[
        'articles' => $articles
        ]);
    }

     /**
    * Requête search
    * 
    * @Route("/search/", name="article_search", methods={"GET"})
    */
    public function search(Request $request, ArticleRepository $articleRepository){

      // récup la requête de l'utilisateur
      $search = $request->query->get("q");

      $articles = $articleRepository->contient($search);

      return $this->render('/Articles/index.html.twig',[
        'articles' => $articles
        ]);
    }

     /**
    * Afficher le formulaire de création d'un article
    * 
    * @Route("/create", name="article_create", methods={"GET"})
    */

   public function create() {    

    return $this->render('/Articles/create.html.twig');
   } 


   /**
     * @Route("/{article}/edit", name="article_edit", methods={"GET"})
     */
    public function edit(Article $article) {
      return $this->render('Articles/create.html.twig', [
          "article" => $article
      ]);
  }

  /**
   * @Route("/{article}/edit", name="article_update", methods={"POST"})
   */
  public function update(Request $request, Article $article) {

      $article->setTitle($request->request->get('title'));
      $article->setContent($request->request->get('content'));
      $article->setShortContent($request->request->get('short_content'));

      $manager = $this->getDoctrine()->getManager();
      $manager->flush();

      return $this->redirectToRoute("article_index");

  }

  /**
   * @Route("/{article}/delete", name="article_delete", methods={"POST"})
   */

   public function delete(Request $request, Article $article) {
      $manager = $this->getDoctrine()->getManager();
      $manager->remove($article);
      $manager->flush();

      return $this->redirectToRoute("article_index");
   }


   /**
    * Affficher un article
    * 
    * @Route("/{articleId}", name="article_show", methods={"GET"})
    */

   public function showArticle(ArticleRepository $articleRepository, int $articleId){

    $article = $articleRepository->find($articleId);
    

    return $this->render('/Articles/show.html.twig',[
      'article' => $article
      ]);

    }    
   /**
    * Traiter le formulaire de création d'un article
    * 
    * @Route("/", name="article_new", methods={"POST"})
    */

   public function new() {   
    // On créée un nouvel object Article
    $article = new Article;
    $article->setTitle($_POST['title']);
    $article->setContent($_POST['content']);
    $article->setShortContent($_POST['short_content']);

    // On récupère l'EntityManager du service Doctrine :
    // Notez que le code est plus court que dans l'expliation ci-dessus !
    $em = $this->getDoctrine()->getManager();

    // On donne l'object en gestion à Doctrine pour qu'il "persiste" l'object, c'est à dire qu'il prépare la requête
    $em->persist($article);

    // On execute effectivement la requête :
    $em->flush();

    return $this->redirectToRoute("app_index");

   }

}

