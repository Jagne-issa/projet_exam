<?php

namespace App\DTO;

class ClientSearch
{
    private ?string $telephone = null;
    private ?string $surname = null;
    private ?string $status = null;
    private bool $compte = false;

    public function __construct(?string $telephone = null, ?string $surname = null, ?string $status = null, bool $compte = false)
    {
        $this->telephone = $telephone;
        $this->surname = $surname;
        $this->status = $status;
        $this->compte = $compte;
    }

    // Getters et Setters
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): void
    {
        $this->surname = $surname;
    }

    public function getStatus(): ?string // Méthode renommée
    {
        return $this->status;
    }

    public function setStatus(?string $status): void // Méthode renommée
    {
        $this->status = $status;
    }

    public function isCompte(): bool
    {
        return $this->compte;
    }

    public function setCompte(bool $compte): void
    {
        $this->compte = $compte;
    }
}
