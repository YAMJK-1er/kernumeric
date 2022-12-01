<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MessageRepository::class), ApiResource()]
#[ORM\HasLifecycleCallbacks]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('getMessage')]
    private $id;

    #[ORM\Column(type: 'date')]
    #[Groups('getMessage')]
    private $date;

    #[ORM\Column(type: 'text')]
    #[Groups('getMessage')]
    private $contenu;

    #[ORM\ManyToMany(targetEntity: Newsletter::class, inversedBy: 'messages')]
    private $newsletters;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('getMessage')]
    private $sujet;

    #[ORM\OneToMany(mappedBy: 'message', targetEntity: Document::class, cascade:['persist'])]
    #[Groups('getMessage')]
    private $documents;

    public function __construct()
    {
        $this->newsletters = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }

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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * @return Collection<int, Newsletter>
     */
    public function getNewsletters(): Collection
    {
        return $this->newsletters;
    }

    public function addNewsletter(Newsletter $newsletter): self
    {
        if (!$this->newsletters->contains($newsletter)) {
            $this->newsletters[] = $newsletter;
        }

        return $this;
    }

    public function removeNewsletter(Newsletter $newsletter): self
    {
        $this->newsletters->removeElement($newsletter);

        return $this;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    #[ORM\PrePersist]
    public function setDateValue()
    {
        $this->date = new \DateTimeImmutable();
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
            $document->setMessage($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getMessage() === $this) {
                $document->setMessage(null);
            }
        }

        return $this;
    }
}
