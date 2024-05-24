<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $transactionId = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column]
    private ?int $entityId = null;

    #[ORM\Column(length: 255)]
    private ?string $entityType = null;

    #[ORM\Column(length: 255)]
    private ?string $merchantTransactionId = null;

    #[ORM\Column(length: 255)]
    private ?string $paymentState = null;

    #[ORM\Column(type: Types::OBJECT, nullable: true)]
    private ?object $paymentInstrument = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(string $transactionId): static
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId(int $entityId): static
    {
        $this->entityId = $entityId;

        return $this;
    }

    public function getEntityType(): ?string
    {
        return $this->entityType;
    }

    public function setEntityType(string $entityType): static
    {
        $this->entityType = $entityType;

        return $this;
    }

    public function getMerchantTransactionId(): ?string
    {
        return $this->merchantTransactionId;
    }

    public function setMerchantTransactionId(string $merchantTransactionId): static
    {
        $this->merchantTransactionId = $merchantTransactionId;

        return $this;
    }

    public function getPaymentState(): ?string
    {
        return $this->paymentState;
    }

    public function setPaymentState(string $paymentState): static
    {
        $this->paymentState = $paymentState;

        return $this;
    }

    public function getPaymentInstrument(): ?object
    {
        return $this->paymentInstrument;
    }

    public function setPaymentInstrument(?object $paymentInstrument): static
    {
        $this->paymentInstrument = $paymentInstrument;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}
