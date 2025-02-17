<?php

namespace App\Entity;

use App\Repository\EmployerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployerRepository::class)]
class Employer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idemployer = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:"2",
    minMessage:"Votre nom doit contenir au moin 2 lettres")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:"2",
    minMessage:"Votre prenom doit contenir au moin 2 lettres")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]  
    #[Assert\Email(message: "l'email {{ value }} n'est pas valide.",)]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\Length(min:"8",max:"8",
    minMessage:"Le numéro de téléphone avoir exactement 8 chiffres",
    maxMessage:"Le numéro de téléphone avoir exactement 8 chiffres")]
    private ?int $Tel = null;

    #[ORM\Column]
    private ?int $salary = null;

    #[ORM\Column(type :"date")]
    private ?\DateTimeInterface $hiredate = null;

    

    #[ORM\Column]
     private ?int $cin = null;

     #[ORM\Column(length: 255)]
     private ?string $poste = null;


     public function getPoste(): ?string
{
    return $this->poste;
}

public function setPoste(string $poste): self
{
    $this->poste = $poste;
    return $this;
}

   public function getIdemployer(): ?int
    {
        return $this->idemployer;
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


    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): self
    {
        $this->salary = $salary;
        return $this;
    }

    public function getHiredate(): ?\DateTimeInterface
    {
        return $this->hiredate;
    }

    public function setHiredate(\DateTimeInterface $hiredate): self
    {
        $this->hiredate = $hiredate;
        return $this;
    }



    
}
