<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    protected $articleRepo;
    public function __construct(ArticleRepository $articleRepo)
    {
        # code...
        $this->articleRepo = $articleRepo;
    }
    /**
     * @Route("/article", name="app_article")
     */
    public function index(): Response
    {
        $articles = $this->articleRepo->findAll();
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles
        ]);
    }

    public function listAction(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request)
    {
        $dql   = "SELECT a FROM AcmeMainBundle:Article a";
        $query = $em->createQuery($dql);

        $articles = $this->articleRepo->findAll();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        // parameters to template
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'pagination' => $pagination
        ]);
    }
}
