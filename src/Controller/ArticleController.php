<?php 

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // AbstractController déjà inclus dans Symfony
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

  // /new est accessible en 2 méthodes:
  // GET : pour AFFICHER le formulaire
  // POST : pour TRAITER le formulaire

  /**
  * @Route("/create", name="article_new", methods={"GET","POST"})
  */
  public function new(Request $request): Response
  {

  // CAS GET (affichage) :
      // On prépare l'article à créer avec le formulaire
      $article = new Article();

      // On prépare le formulaire : on utilise le service createForm avec en arguments: le formulaire généré (ArticleType) et l'objet traité par le formulaire ($article)
      $form = $this->createForm(ArticleType::class, $article);

  // CAS POST (traitement) :
      // On indique au formulaire de traiter la requête
      $form->handleRequest($request); // action des SETters est géré automatiquement ici

      // Si le formulaire a été envoyé et est valide, on le traite
      if ($form->isSubmitted() && $form->isValid()) {

          // On enregistre la donnée
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($article);
          $entityManager->flush();

          // On redirige vers la page article_index
          return $this->redirectToRoute('article_index');
      }

  // CAS GET ou CAS POST SI FORMULAIRE INVALIDE (if ci-dessus) :
  // On affiche le formulaire
      return $this->render('/Articles/create.html.twig', [
          'product' => $article,
          'form' => $form->createView(),
      ]);
  }

    /** 
     * @Route("/{article}/edit", name="article_edit", methods={"GET", "POST"})
     */
    public function update(Request $request, Article $article) {
      
      // On prépare le formulaire : on utilise le service createForm avec en arguments: le formulaire généré (ArticleType) et l'objet traité par le formulaire ($article)
      $form = $this->createForm(ArticleType::class, $article);

      // CAS POST (traitement) :
        // On indique au formulaire de traiter la requête
        $form->handleRequest($request);

        // Si le formulaire a été envoyé et est valide, on le traite
        if ($form->isSubmitted() && $form->isValid()) {

            // On enregistre la donnée
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            // On redirige vers la page article_index
            return $this->redirectToRoute('article_index');
        }

    // CAS GET ou CAS POST SI FORMULAIRE INVALIDE (if ci-dessus) :
    // On affiche le formulaire
        return $this->render('/Articles/create.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);

      
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

  

}

