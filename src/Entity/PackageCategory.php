<?php

namespace App\Entity;

use App\Repository\PackageCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;



#[ORM\Entity(repositoryClass: PackageCategoryRepository::class)]
class PackageCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Slug(fields: ['name'])]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Package::class)]
    private Collection $packages;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image;

    #[ORM\Column]
    private ?bool $trending = false;

    /**
     * @return string|null
     */
    public function getImage() : ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image) : void
    {
        $this->image = $image;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->packages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    /**
     * @return Collection<int, Package>
     */
    public function getPackages(): Collection
    {
        return $this->packages;
    }

    public function addPackage(Package $package): static
    {
        if (!$this->packages->contains($package)) {
            $this->packages->add($package);
            $package->setCategory($this);
        }

        return $this;
    }

    public function removePackage(Package $package): static
    {
        if ($this->packages->removeElement($package)) {
            // set the owning side to null (unless already changed)
            if ($package->getCategory() === $this) {
                $package->setCategory(null);
            }
        }

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
}
