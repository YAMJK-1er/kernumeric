<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DocumentRepository::class), ApiResource()]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['getActivite', 'getAssociation', 'getMembre', 'getMessage', 'getPartenaire'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['getActivite', 'getAssociation', 'getMembre', 'getMessage', 'getPartenaire'])]
    private $type;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['getActivite', 'getAssociation', 'getMembre', 'getMessage', 'getPartenaire'])]
    private $url;

    #[ORM\ManyToOne(targetEntity: Activite::class, inversedBy: 'documents', cascade:['persist'])]
    private $activite;

    #[ORM\ManyToOne(targetEntity: Association::class, inversedBy: 'documents', cascade:['persist'])]
    private $association;

    #[ORM\ManyToOne(targetEntity: Mission::class, inversedBy: 'documents')]
    private $mission;

    #[ORM\ManyToOne(targetEntity: Membre::class, inversedBy: 'documents', cascade:['persist'], )]
    private $membre;

    #[ORM\ManyToOne(targetEntity: Partenaire::class, inversedBy: 'documents', cascade:['persist'])]
    private $partenaire;

    #[ORM\ManyToOne(targetEntity: Message::class, inversedBy: 'documents', cascade:['persist'])]
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getActivite(): ?Activite
    {
        return $this->activite;
    }

    public function setActivite(?Activite $activite): self
    {
        $this->activite = $activite;

        return $this;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): self
    {
        $this->association = $association;

        return $this;
    }

    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    public function setMission(?Mission $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    public function setMembre(?Membre $membre): self
    {
        $this->membre = $membre;

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

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }
}
