<?php

namespace App\Entity;

use App\Repository\PackageEnquiryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PackageEnquiryRepository::class)]
class PackageEnquiry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fromDate = null;

    #[ORM\Column]
    private ?int $adults = null;

    #[ORM\Column]
    private ?int $childrens = null;

    #[ORM\Column]
    private ?int $infants = null;

    #[ORM\OneToMany(mappedBy: 'packageEnquiry', targetEntity: Package::class)]
    private Collection $package;

    #[ORM\Column]
    private ?float $amount = null;

    public function __construct()
    {
        $this->package = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromDate(): ?\DateTimeInterface
    {
        return $this->fromDate;
    }

    public function setFromDate(\DateTimeInterface $fromDate): static
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    public function getAdults(): ?int
    {
        return $this->adults;
    }

    public function setAdults(int $adults): static
    {
        $this->adults = $adults;

        return $this;
    }

    public function getChildrens(): ?int
    {
        return $this->childrens;
    }

    public function setChildrens(int $childrens): static
    {
        $this->childrens = $childrens;

        return $this;
    }

    public function getInfants(): ?int
    {
        return $this->infants;
    }

    public function setInfants(int $infants): static
    {
        $this->infants = $infants;

        return $this;
    }

    /**
     * @return Collection<int, Package>
     */
    public function getPackage(): Collection
    {
        return $this->package;
    }

    public function addPackage(Package $package): static
    {
        if (!$this->package->contains($package)) {
            $this->package->add($package);
            $package->setPackageEnquiry($this);
        }

        return $this;
    }

    public function removePackage(Package $package): static
    {
        if ($this->package->removeElement($package)) {
            // set the owning side to null (unless already changed)
            if ($package->getPackageEnquiry() === $this) {
                $package->setPackageEnquiry(null);
            }
        }

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
}
