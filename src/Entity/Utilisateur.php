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
    ]
)]

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Employer::class)]
    #[ORM\JoinColumn(name: "employer", referencedColumnName: "idemployer")]
    private ?Employer $employer = null; // Renommé pour correspondre à votre template

   

    #[ORM\Column(length: 255)]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    private ?string $apropos = null;

    #[ORM\Column(length: 255)]
    // #[Assert\NotBlank(message: "champ obligatoire")]
    private ?string $mdp = null;

    

    

     #[ORM\Column(length: 255)]
     private ?string $image = null;

    

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




public function getLogin(): ?string
{
    return $this->login;
}

public function setLogin(string $login): self
{
    $this->login = $login;
    return $this;
}

public function getEmployer(): ?Employer
    {
        return $this->employer;
    }

public function setEmployer(?Employer $employer): self
    {
        $this->employer = $employer;
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





public function getImage(): ?string
{
    return $this->image;
}

public function setImage(string $image): self
{
    $this->image = $image;
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
