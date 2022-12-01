<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MembreRepository::class)]
class Membre extends Personne
{
    #[ORM\OneToMany(mappedBy: 'membre', targetEntity: Document::class, cascade:['persist'])]
    #[Groups('getMembre')]
    private $documents;

    #[ORM\Column(type: 'text')]
    #[Groups('getMembre')]
    private $infos;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
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
            $document->setMembre($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getMembre() === $this) {
                $document->setMembre(null);
            }
        }

        return $this;
    }

    public function getInfos(): ?string
    {
        return $this->infos;
    }

    public function setInfos(string $infos): self
    {
        $this->infos = $infos;

        return $this;
    }
}
