<?php

namespace App\Entity;

use App\Repository\HistoriqueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoriqueRepository::class)
 */
class Historique
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datemodif;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="historiques")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tableModifiee;

    /**
     * @ORM\Column(type="json")
     */
    private $modifications = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typeOperation;

    /**
     * @ORM\Column(type="integer")
     */
    private $idligne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatemodif(): ?\DateTimeInterface
    {
        return $this->datemodif;
    }

    public function setDatemodif(\DateTimeInterface $datemodif): self
    {
        $this->datemodif = $datemodif;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTableModifiee(): ?string
    {
        return $this->tableModifiee;
    }

    public function setTableModifiee(string $tableModifiee): self
    {
        $this->tableModifiee = $tableModifiee;

        return $this;
    }

    public function getModifications(): ?array
    {
        return $this->modifications;
        
    }

    public function setModifications(array $modifications): self
    {
        $this->modifications = $modifications;

        return $this;
    }

    public function getTypeOperation(): ?string
    {
        return $this->typeOperation;
    }

    public function setTypeOperation(?string $typeOperation): self
    {
        $this->typeOperation = $typeOperation;

        return $this;
    }

    public function getIdligne(): ?int
    {
        return $this->idligne;
    }

    public function setIdligne(int $idligne): self
    {
        $this->idligne = $idligne;

        return $this;
    }
    
}
