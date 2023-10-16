<?php

namespace App\Entity;

use App\Repository\PackageMediaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: PackageMediaRepository::class)]
#[Gedmo\Uploadable(pathMethod: "getDir", filenameGenerator: "SHA1")]
class PackageMedia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $originalName = null;

    #[ORM\Column(length: 255)]
    private ?string $encodedName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $terms = null;

    #[Assert\File(
        maxSize: "1M",
        mimeTypes: ["image/jpeg", "image/png"],
        maxSizeMessage: "The maximum allowed file size is 1MB.",
        mimeTypesMessage: "Only image files are allowed."
    )]
    private $imageFile;

    #[ORM\Column(length: 63, nullable: true)]
    #[Gedmo\UploadableFileName]
    private $image;

    #[ORM\ManyToOne(inversedBy: 'packageMedia')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Package $package = null;

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImageFile($imageFile): void
    {
        $this->imageFile = $imageFile;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDir() {
        return "uploads/";
    }

    public function getWebPath(){
        return "{$this->getDir()}{$this->image}";
    }

    public function __toString()
    {
        return $this->originalName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): static
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getEncodedName(): ?string
    {
        return $this->encodedName;
    }

    public function setEncodedName(string $encodedName): static
    {
        $this->encodedName = $encodedName;

        return $this;
    }

    public function getTerms(): ?string
    {
        return $this->terms;
    }

    public function setTerms(?string $terms): static
    {
        $this->terms = $terms;

        return $this;
    }

    public function getPackage(): ?Package
    {
        return $this->package;
    }

    public function setPackage(?Package $package): static
    {
        $this->package = $package;

        return $this;
    }
}
