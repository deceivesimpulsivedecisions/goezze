<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cityCode = null;

    #[ORM\Column(length: 255)]
    private ?string $cityName = null;

    #[ORM\Column(length: 255)]
    private ?string $countryName = null;

    #[ORM\Column(length: 2)]
    private ?string $countryCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCityCode(): ?int
    {
        return $this->cityCode;
    }

    public function setCityCode(int $cityCode): static
    {
        $this->cityCode = $cityCode;

        return $this;
    }

    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    public function setCityName(string $cityName): static
    {
        $this->cityName = $cityName;

        return $this;
    }

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    public function setCountryName(string $countryName): static
    {
        $this->countryName = $countryName;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): static
    {
        $this->countryCode = $countryCode;

        return $this;
    }
}
