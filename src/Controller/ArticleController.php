<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Stock;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/article/new', name: 'app_article_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Configuration de l'article
                $article->setAuthor($this->getUser());
                $article->setPublishDate(new \DateTime());

                // Configuration du stock
                $stock = new Stock();
                $stock->setArticle($article);
                $stock->setQuantity($form->get('quantity')->getData());
                $article->setStock($stock);

                // Persistance des données
                $entityManager->persist($article);
                $entityManager->persist($stock);
                $entityManager->flush();

                $this->addFlash('success', 'Article mis en ligne avec succès !');
                return $this->redirectToRoute('app_articles');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la création de l\'article.');
            }
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/{id}', name: 'app_article_show')]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
} 