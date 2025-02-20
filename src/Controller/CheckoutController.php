<?php

namespace App\Controller;

use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'app_checkout')]
    public function index(CartRepository $cartRepository): Response
    {
        $user = $this->getUser();
        $cartItems = $cartRepository->findBy(['user' => $user]);
        
        if (empty($cartItems)) {
            $this->addFlash('error', 'Votre panier est vide');
            return $this->redirectToRoute('app_cart');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->getArticle()->getPrice() * $item->getQuantity();
        }

        return $this->render('checkout/index.html.twig', [
            'cart_items' => $cartItems,
            'total' => $total,
        ]);
    }
} 