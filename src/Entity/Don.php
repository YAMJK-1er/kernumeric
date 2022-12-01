<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DonRepository::class), ApiResource()]
#[ORM\HasLifecycleCallbacks]
class Don
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['getDonateur', 'getDon'])]
    private $id;

    #[ORM\Column(type: 'date' , nullable:true)]
    #[Groups(['getDonateur', 'getDon'])]
    private $date;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['getDonateur', 'getDon'])]
    private $typePaiement;

    #[ORM\Column(type: 'integer')]
    #[Groups(['getDonateur', 'getDon'])]
    private $valeur;

    #[ORM\ManyToOne(targetEntity: Donateur::class, inversedBy: 'dons')]
    #[Groups('getDon')]
    private $donateur;

    #[ORM\ManyToOne(targetEntity: Partenaire::class, inversedBy: 'dons')]
    private $partenaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTypePaiement(): ?string
    {
        return $this->typePaiement;
    }

    public function setTypePaiement(string $typePaiement): self
    {
        $this->typePaiement = $typePaiement;

        return $this;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(?int $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getDonateur(): ?Donateur
    {
        return $this->donateur;
    }

    public function setDonateur(?Donateur $donateur): self
    {
        $this->donateur = $donateur;

        return $this;
    }

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(?Partenaire $partenaire): self
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    #[ORM\PrePersist]
    public function SetDateValue()
    {
        $this->date = new \DateTimeImmutable();
    }
}
