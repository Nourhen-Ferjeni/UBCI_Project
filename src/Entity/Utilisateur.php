<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Table(
    name: "utilisateur",
    uniqueConstraints: [
        new ORM\UniqueConstraint(name: "login", columns: ["login"]),
        new ORM\UniqueConstraint(name: "email", columns: ["email"])
    ]
)]

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:"2",
    minMessage:"Votre nom doit contenir au moin 2 lettres")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:"2",
    minMessage:"Votre nom doit contenir au moin 2 lettres")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]  
    #[Assert\Email(message: "l'email {{ value }} is not a valid email.",)]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\Length(min:"8",max:"8",
    minMessage:"Le numéro de téléphone avoir exactement 8 chiffres",
    maxMessage:"Le numéro de téléphone avoir exactement 8 chiffres")]
    private ?int $Tel = null;

    #[ORM\Column(length: 255)]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    private ?string $apropos = null;

    #[ORM\Column(length: 255)]
    // #[Assert\NotBlank(message: "champ obligatoire")]
    private ?string $mdp = null;

    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    #[ORM\Column]
    #[Assert\Length(min:"8",max:"8",
     minMessage:"Le numéro de téléphone avoir exactement 8 chiffres",
     maxMessage:"Le numéro de téléphone avoir exactement 8 chiffres")]
     private ?int $cin = null;

     #[ORM\Column(length: 255)]
     private ?string $image = null;

     #[ORM\Column(length: 255)]
     private ?string $poste = null;

     #[ORM\Column]
    private ?int $role = null;

    #[ORM\Column(type :"date")]
    private ?\DateTimeInterface $dateajout = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getDateajout(): ?\DateTimeInterface
{
    return $this->dateajout;
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

public function getEmail(): ?string
{
    return $this->email;
}

public function setEmail(string $email): self
{
    $this->email = $email;
    return $this;
}

public function getTel(): ?int
{
    return $this->Tel;
}

public function setTel(int $Tel): self
{
    $this->Tel = $Tel;
    return $this;
}

public function getLogin(): ?string
{
    return $this->login;
}

public function setLogin(string $login): self
{
    $this->login = $login;
    return $this;
}

public function getApropos(): ?string
{
    return $this->apropos;
}

public function setApropos(string $apropos): self
{
    $this->apropos = $apropos;
    return $this;
}

public function getMdp(): ?string
{
    return $this->mdp;
}

public function setMdp(string $mdp): self
{
    $this->mdp = $mdp;
    return $this;
}

public function getGenre(): ?string
{
    return $this->genre;
}

public function setGenre(string $genre): self
{
    $this->genre = $genre;
    return $this;
}

public function getCin(): ?int
{
    return $this->cin;
}

public function setCin(int $cin): self
{
    $this->cin = $cin;
    return $this;
}

public function getImage(): ?string
{
    return $this->image;
}

public function setImage(string $image): self
{
    $this->image = $image;
    return $this;
}

public function getPoste(): ?string
{
    return $this->poste;
}

public function setPoste(string $poste): self
{
    $this->poste = $poste;
    return $this;
}

public function getRole(): ?int
{
    return $this->role;
}

public function setRole(int $role): self
{
    $this->role = $role;
    return $this;
}

public function __construct()
    {
        $this->role = 1; // Définit le rôle par défaut à 1
        $this->dateajout = (new \DateTime())->setTime(0, 0, 0);

    }

}
