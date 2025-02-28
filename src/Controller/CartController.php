<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Cart;
use App\Entity\Invoice;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartRepository $cartRepository): Response
    {
        $cartItems = $cartRepository->findBy(['user' => $this->getUser()]);
        
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->getTotal();
        }

        return $this->render('cart/index.html.twig', [
            'cart_items' => $cartItems,
            'total' => $total,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function add(Article $article, Request $request, EntityManagerInterface $entityManager, CartRepository $cartRepository): Response
    {
        $quantity = max(1, $request->request->getInt('quantity', 1));
        
        if ($quantity > $article->getQuantity()) {
            $this->addFlash('error', 'Quantité demandée non disponible.');
            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
        }

        // Vérifier si l'article est déjà dans le panier
        $cartItem = $cartRepository->findOneBy([
            'user' => $this->getUser(),
            'article' => $article
        ]);

        if ($cartItem) {
            // Mettre à jour la quantité
            $newQuantity = $cartItem->getQuantity() + $quantity;
            if ($newQuantity > $article->getQuantity()) {
                $this->addFlash('error', 'Quantité totale demandée non disponible.');
                return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
            }
            $cartItem->setQuantity($newQuantity);
        } else {
            // Créer un nouvel élément de panier
            $cartItem = new Cart();
            $cartItem->setUser($this->getUser());
            $cartItem->setArticle($article);
            $cartItem->setQuantity($quantity);
            $entityManager->persist($cartItem);
        }

        $entityManager->flush();
        $this->addFlash('success', 'Article ajouté au panier.');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/update/{id}', name: 'app_cart_update', methods: ['POST'])]
    public function update(Cart $cartItem, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur est bien le propriétaire du panier
        if ($cartItem->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $quantity = max(1, $request->request->getInt('quantity', 1));
        
        if ($quantity > $cartItem->getArticle()->getQuantity()) {
            $this->addFlash('error', 'Quantité demandée non disponible.');
        } else {
            $cartItem->setQuantity($quantity);
            $entityManager->flush();
            $this->addFlash('success', 'Panier mis à jour.');
        }

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'app_cart_remove', methods: ['POST'])]
    public function remove(Cart $cartItem, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur est bien le propriétaire du panier
        if ($cartItem->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $entityManager->remove($cartItem);
        $entityManager->flush();
        
        $this->addFlash('success', 'Article retiré du panier.');
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/checkout', name: 'app_cart_checkout', methods: ['POST'])]
    public function checkout(Request $request, EntityManagerInterface $entityManager, CartRepository $cartRepository): Response
    {
        $cartItems = $cartRepository->findBy(['user' => $this->getUser()]);
        
        if (empty($cartItems)) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->getTotal();
        }

        $user = $this->getUser();
        if ($user->getBalance() < $total) {
            $this->addFlash('error', 'Solde insuffisant pour effectuer cet achat.');
            return $this->redirectToRoute('app_cart');
        }

        // Créer la facture
        $invoice = new Invoice();
        $invoice->setUser($user);
        $invoice->setAmount($total);
        $invoice->setTransactionDate(new \DateTime());
        $invoice->setBillingAddress($request->request->get('address', 'Adresse non spécifiée'));
        $invoice->setBillingZipCode($request->request->get('zipcode', '00000'));
        $invoice->setBillingCity($request->request->get('city', 'Ville non spécifiée'));
        
        $entityManager->persist($invoice);

        // Mettre à jour le stock et le solde
        $user->setBalance($user->getBalance() - $total);
        
        foreach ($cartItems as $item) {
            $article = $item->getArticle();
            $article->decreaseQuantity($item->getQuantity());
            $entityManager->remove($item);
        }

        $entityManager->flush();
        
        $this->addFlash('success', 'Commande effectuée avec succès !');
        return $this->redirectToRoute('app_invoices');
    }
} 