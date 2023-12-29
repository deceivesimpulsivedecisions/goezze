<?php

namespace App\Entity;

use App\Repository\PackageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PackageRepository::class)]
class Package
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 2000)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\ManyToOne(inversedBy: 'packages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Admin $createdBy = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $deletedAt = null;

    #[ORM\ManyToOne(inversedBy: 'packages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PackageCategory $category = null;

    #[ORM\OneToMany(mappedBy: 'package', targetEntity: PackageItenary::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $packageItinerary;

    #[ORM\OneToMany(mappedBy: 'package', targetEntity: PackageMedia::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $packageMedia;

    #[ORM\ManyToOne(inversedBy: 'packages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Destination $destination = null;

    #[ORM\ManyToOne(inversedBy: 'packages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PackageType $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $inclusions = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $exclusions = null;

    #[ORM\Column]
    private ?bool $isActive = true;

    #[ORM\Column]
    private ?bool $trending = false;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'package')]
    private ?PackageEnquiry $packageEnquiry = null;

    public function __construct()
    {
        $this->packageItinerary = new ArrayCollection();
        $this->packageMedia = new ArrayCollection();
        $this->generateSlug();
    }

    public function generateSlug(): void
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->getTitle())));
        $this->setSlug($slug);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        $this->generateSlug();

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getCreatedBy(): ?Admin
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?Admin $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTime $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getCategory(): ?PackageCategory
    {
        return $this->category;
    }

    public function setCategory(?PackageCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, PackageItenary>
     */
    public function getPackageItinerary(): Collection
    {
        return $this->packageItinerary;
    }

    public function addPackageItinerary(PackageItenary $packageItinerary): static
    {
        if (!$this->packageItinerary->contains($packageItinerary)) {
            $this->packageItinerary->add($packageItinerary);
            $packageItinerary->setPackage($this);
        }

        return $this;
    }

    public function removePackageItinerary(PackageItenary $packageItinerary): static
    {
        if ($this->packageItinerary->removeElement($packageItinerary)) {
            // set the owning side to null (unless already changed)
            if ($packageItinerary->getPackage() === $this) {
                $packageItinerary->setPackage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PackageMedia>
     */
    public function getPackageMedia(): Collection
    {
        return $this->packageMedia;
    }

    public function addPackageMedium(PackageMedia $packageMedium): static
    {
        if (!$this->packageMedia->contains($packageMedium)) {
            $this->packageMedia->add($packageMedium);
            $packageMedium->setPackage($this);
        }

        return $this;
    }

    public function removePackageMedium(PackageMedia $packageMedium): static
    {
        if ($this->packageMedia->removeElement($packageMedium)) {
            // set the owning side to null (unless already changed)
            if ($packageMedium->getPackage() === $this) {
                $packageMedium->setPackage(null);
            }
        }

        return $this;
    }

    public function getDestination(): ?Destination
    {
        return $this->destination;
    }

    public function setDestination(?Destination $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getType(): ?PackageType
    {
        return $this->type;
    }

    public function setType(?PackageType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getInclusions(): ?string
    {
        return $this->inclusions;
    }

    public function setInclusions(?string $inclusions): static
    {
        $this->inclusions = $inclusions;

        return $this;
    }

    public function getExclusions(): ?string
    {
        return $this->exclusions;
    }

    public function setExclusions(?string $exclusions): static
    {
        $this->exclusions = $exclusions;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isTrending(): ?bool
    {
        return $this->trending;
    }

    public function setTrending(bool $trending): static
    {
        $this->trending = $trending;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPackageEnquiry(): ?PackageEnquiry
    {
        return $this->packageEnquiry;
    }

    public function setPackageEnquiry(?PackageEnquiry $packageEnquiry): static
    {
        $this->packageEnquiry = $packageEnquiry;

        return $this;
    }
}
