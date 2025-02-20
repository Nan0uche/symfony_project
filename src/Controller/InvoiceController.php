<?php

namespace App\Controller;

use App\Repository\InvoiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends AbstractController
{
    #[Route('/invoices', name: 'app_invoices')]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        $user = $this->getUser();
        $invoices = $invoiceRepository->findBy(['user' => $user], ['transactionDate' => 'DESC']);

        return $this->render('invoice/index.html.twig', [
            'invoices' => $invoices,
        ]);
    }

    #[Route('/invoice/{id}', name: 'app_invoice_show')]
    public function show(int $id, InvoiceRepository $invoiceRepository): Response
    {
        $invoice = $invoiceRepository->find($id);

        if (!$invoice || $invoice->getUser() !== $this->getUser()) {
            throw $this->createNotFoundException('Facture non trouvée');
        }

        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }
} 