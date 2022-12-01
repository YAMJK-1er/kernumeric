<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AssociationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AssociationRepository::class), ApiResource()]
class Association
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('getAssociation')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('getAssociation')]
    private $nom;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('getAssociation')]
    private $numero;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('getAssociation')]
    private $lienLogo;

    #[ORM\Column(type: 'text')]
    #[Groups('getAssociation')]
    private $objet;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('getAssociation')]
    private $siegeSocial;

    #[ORM\Column(type: 'date')]
    #[Groups('getAssociation')]
    private $dateCreation;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('getAssociation')]
    private $recipisse;

    #[ORM\OneToMany(mappedBy: 'association', targetEntity: Document::class, cascade:['persist'])]
    #[Groups('getAssociation')]
    private $documents;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getLienLogo(): ?string
    {
        return $this->lienLogo;
    }

    public function setLienLogo(string $lienLogo): self
    {
        $this->lienLogo = $lienLogo;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getSiegeSocial(): ?string
    {
        return $this->siegeSocial;
    }

    public function setSiegeSocial(string $siegeSocial): self
    {
        $this->siegeSocial = $siegeSocial;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getRecipisse(): ?string
    {
        return $this->recipisse;
    }

    public function setRecipisse(string $recipisse): self
    {
        $this->recipisse = $recipisse;

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
            $document->setAssociation($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getAssociation() === $this) {
                $document->setAssociation(null);
            }
        }

        return $this;
    }
}
