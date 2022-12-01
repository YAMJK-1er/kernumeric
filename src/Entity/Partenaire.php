<?php

namespace App\Entity;

use App\Repository\PartenaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PartenaireRepository::class)]
class Partenaire extends Personne
{
    #[ORM\Column(type: 'string', length: 255, nullable:true)]
    #[Groups('getPartenaire')]
    private $urlSite;

    #[ORM\OneToMany(mappedBy: 'partenaire', targetEntity: Don::class)]
    private $dons;

    #[ORM\OneToMany(mappedBy: 'partenaire', targetEntity: Document::class, cascade:['persist'])]
    #[Groups('getPartenaire')]
    private $documents;

    public function __construct()
    {
        $this->dons = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }

    public function getUrlSite(): ?string
    {
        return $this->urlSite;
    }

    public function setUrlSite(string $urlSite): self
    {
        $this->urlSite = $urlSite;

        return $this;
    }

    /**
     * @return Collection<int, Don>
     */
    public function getDons(): Collection
    {
        return $this->dons;
    }

    public function addDon(Don $don): self
    {
        if (!$this->dons->contains($don)) {
            $this->dons[] = $don;
            $don->setPartenaire($this);
        }

        return $this;
    }

    public function removeDon(Don $don): self
    {
        if ($this->dons->removeElement($don)) {
            // set the owning side to null (unless already changed)
            if ($don->getPartenaire() === $this) {
                $don->setPartenaire(null);
            }
        }

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
            $document->setPartenaire($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getPartenaire() === $this) {
                $document->setPartenaire(null);
            }
        }

        return $this;
    }
}
