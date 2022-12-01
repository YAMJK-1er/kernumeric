<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ActiviteRepository::class), ApiResource()]
class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('getActivite')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('getActivite')]
    private $intitule;

    #[ORM\Column(type: 'boolean', nullable: true)]
    #[Groups('getActivite')]
    private $statut;

    #[ORM\Column(type: 'date')]
    #[Groups('getActivite')]
    private $debut;

    #[ORM\Column(type: 'date')]
    #[Groups('getActivite')]
    private $fin;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups('getActivite')]
    private $finReel;

    #[ORM\OneToMany(mappedBy: 'activite', targetEntity: Document::class, cascade:['persist'])]
    #[Groups('getActivite')]
    private $documents;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(?bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getFinReel(): ?\DateTimeInterface
    {
        return $this->finReel;
    }

    public function setFinReel(?\DateTimeInterface $finReel): self
    {
        $this->finReel = $finReel;

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setActivite($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getActivite() === $this) {
                $document->setActivite(null);
            }
        }

        return $this;
    }
}
