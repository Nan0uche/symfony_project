<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $transactionDate = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?float $amount = null;

    #[ORM\Column(length: 255)]
    private ?string $billingAddress = null;

    #[ORM\Column(length: 100)]
    private ?string $billingCity = null;

    #[ORM\Column(length: 10)]
    private ?string $billingZipCode = null;

    // Getters et setters...
} 