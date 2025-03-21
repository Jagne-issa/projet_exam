<?php

// src/Entity/Dette.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\DetteRepository")]
#[ORM\Table(name: "dette")]
class Dette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "date")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: "float")]
    private ?float $montant = null;

    #[ORM\Column(type: "float")]
    private ?float $montantVerser = null;

    #[ORM\Column(type: "float")]
    private ?float $montantRestant = null;

    #[ORM\ManyToMany(targetEntity: "App\Entity\Article")]
    #[ORM\JoinTable(name: "dettes_articles")]
    private $articles;

    // Constructor to initialize properties if necessary
    public function __construct()
    {
        // Example: $this->articles = new ArrayCollection(); // If articles is a collection
    }

    // Getters and Setters

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

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    public function getMontantVerser(): ?float
    {
        return $this->montantVerser;
    }

    public function setMontantVerser(float $montantVerser): self
    {
        $this->montantVerser = $montantVerser;
        return $this;
    }

    public function getMontantRestant(): ?float
    {
        return $this->montantRestant;
    }

    public function setMontantRestant(float $montantRestant): self
    {
        $this->montantRestant = $montantRestant;
        return $this;
    }

    public function getArticles()
    {
        return $this->articles;
    }

    public function setArticles($articles): self
    {
        $this->articles = $articles;
        return $this;
    }
}
























// // src/Entity/Dette.php

// namespace App\Entity;

// use App\Repository\DetteRepository;
// use Doctrine\ORM\Mapping as ORM;

// #[ORM\Entity(repositoryClass: DetteRepository::class)]
// #[ORM\Table(name: 'dette')]
// class Dette
// {
//     #[ORM\Id]
//     #[ORM\GeneratedValue]
//     #[ORM\Column]
//     private ?int $id = null;

//     #[ORM\Column(type: "float")]
//     private ?float $montant = null;

//     #[ORM\Column(type: "float")]
//     private ?float $montantVerser = null;

//     #[ORM\Column(type: 'string', length: 50)]
//     private ?string $status = null;

//     #[ORM\Column(type: "datetime_immutable", name: "create_at")]
//     private ?\DateTimeImmutable $createAt = null;

//     #[ORM\Column(type: "datetime_immutable", name: "update_at")]
//     private ?\DateTimeImmutable $updateAt = null;

//     #[ORM\ManyToOne(inversedBy: 'dettes')]
//     #[ORM\JoinColumn(nullable: false)]
//     private ?Client $client = null;

//     public function __construct()
//     {
//         $this->createAt = new \DateTimeImmutable();
//         $this->updateAt = new \DateTimeImmutable();
//     }

//     public function getId(): ?int
//     {
//         return $this->id;
//     }

//     public function getMontant(): ?float
//     {
//         return $this->montant;
//     }

//     public function setMontant(float $montant): static
//     {
//         $this->montant = $montant;
//         return $this;
//     }

//     public function getMontantVerser(): ?float
//     {
//         return $this->montantVerser;
//     }

//     public function setMontantVerser(float $montantVerser): static
//     {
//         $this->montantVerser = $montantVerser;
//         return $this;
//     }

//     public function getCreateAt(): ?\DateTimeImmutable
//     {
//         return $this->createAt;
//     }

//     public function setCreateAt(\DateTimeImmutable $createAt): static
//     {
//         $this->createAt = $createAt;
//         return $this;
//     }

//     public function getUpdateAt(): ?\DateTimeImmutable
//     {
//         return $this->updateAt;
//     }

//     public function setUpdateAt(\DateTimeImmutable $updateAt): static
//     {
//         $this->updateAt = $updateAt;
//         return $this;
//     }

//     public function getClient(): ?Client
//     {
//         return $this->client;
//     }

//     public function setClient(?Client $client): static
//     {
//         $this->client = $client;
//         return $this;
//     }

//     public function getStatus(): ?string // Ajoutez le getter pour status
//     {
//         return $this->status;
//     }

//     public function setStatus(?string $status): static // Ajoutez le setter pour status
//     {
//         $this->status = $status;
//         return $this;
//     }
// }
