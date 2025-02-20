<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function index(Request $request, ArticleRepository $articleRepository, PaginatorInterface $paginator): Response
    {
        $query = $articleRepository->createQueryBuilder('a')
            ->orderBy('a.publishDate', 'DESC')
            ->getQuery();

        $articles = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12 // Nombre d'articles par page
        );

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'app_article_show')]
    public function show(int $id, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
} 