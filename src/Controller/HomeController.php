<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $latestArticles = $articleRepository->findBy(
            [], // critères
            ['publishDate' => 'DESC'], // tri
            6 // limite
        );

        return $this->render('home/index.html.twig', [
            'latest_articles' => $latestArticles,
        ]);
    }
}
