<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    protected $articleRepo;

    public function __construct(ArticleRepository $articleRepo)
    {
        $this->articleRepo = $articleRepo;
    }
    /**
     * @Route("/", name="app_article")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $this->articleRepo->findAllPaginated($request->query->getInt('page', 1));
        // parameters to template
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'pagination' => $pagination
        ]);
    }
}
