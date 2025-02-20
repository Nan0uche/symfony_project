<?php

namespace App\Controller;

use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartRepository $cartRepository): Response
    {
        $user = $this->getUser();
        $cartItems = $cartRepository->findBy(['user' => $user]);
        
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->getArticle()->getPrice() * $item->getQuantity();
        }

        return $this->render('cart/index.html.twig', [
            'cart_items' => $cartItems,
            'total' => $total,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add')]
    public function add(): Response
    {
        // La logique d'ajout sera implémentée plus tard
        $this->addFlash('success', 'Article ajouté au panier');
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove')]
    public function remove(): Response
    {
        // La logique de suppression sera implémentée plus tard
        $this->addFlash('success', 'Article retiré du panier');
        return $this->redirectToRoute('app_cart');
    }
} 