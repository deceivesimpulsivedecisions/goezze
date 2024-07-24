<?php

namespace App\Entity;

use App\Repository\AirportsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AirportsRepository::class)]
class Airports
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $origin = null;

    #[ORM\Column(length: 8)]
    private ?string $airportCode = null;

    #[ORM\Column(length: 255)]
    private ?string $airportName = null;

    #[ORM\Column(length: 255)]
    private ?string $airportCity = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrigin(): ?int
    {
        return $this->origin;
    }

    public function setOrigin(int $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function getAirportCode(): ?string
    {
        return $this->airportCode;
    }

    public function setAirportCode(string $airportCode): static
    {
        $this->airportCode = $airportCode;

        return $this;
    }

    public function getAirportName(): ?string
    {
        return $this->airportName;
    }

    public function setAirportName(string $airportName): static
    {
        $this->airportName = $airportName;

        return $this;
    }

    public function getAirportCity(): ?string
    {
        return $this->airportCity;
    }

    public function setAirportCity(string $airportCity): static
    {
        $this->airportCity = $airportCity;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }
}
