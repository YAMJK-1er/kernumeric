<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name:'role', type:'string')]
#[ORM\DiscriminatorMap(['adherent' => 'Adherent', 'contact' => 'Contact', 'donateur' => 'Donateur', 'membre' => 'Membre', 'newsletter' => 'Newsletter', 'partenaire' => 'Partenaire'])]
abstract class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['getDonateur', 'getDon', 'getMembre', 'getPartenaire'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['getDonateur', 'getDon', 'getMembre', 'getPartenaire'])]
    private $nom;

    #[ORM\Column(type: 'string', length: 255, nullable:true)]
    #[Groups(['getDonateur', 'getDon', 'getMembre', 'getPartenaire'])]
    private $prenom;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['getDonateur', 'getDon', 'getMembre', 'getPartenaire'])]
    private $telephone;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['getDonateur', 'getDon', 'getMembre', 'getPartenaire'])]
    private $pays;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['getDonateur', 'getDon', 'getMembre', 'getPartenaire'])]
    private $ville;

    #[ORM\Column(type: 'text' , nullable:true)]
    #[Groups(['getDonateur', 'getDon', 'getMembre', 'getPartenaire'])]
    private $commentaire;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['getDonateur', 'getDon', 'getMembre', 'getPartenaire'])]
    private $email;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function __toString()
    {
        return $this->prenom . ' ' . $this->nom;
    }
}
