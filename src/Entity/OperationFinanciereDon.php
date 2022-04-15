<?php

namespace App\Entity;

use App\Repository\OperationFinanciereDonRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OperationFinanciereDonRepository::class)
 */
class OperationFinanciereDon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomdonataire;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stipulation;
    /**
     * @ORM\ManyToOne(targetEntity=Caisse::class, inversedBy="operationFinanciereDons")
     */
    private $caisse;

    /**
     * @ORM\OneToOne(targetEntity=OperationFinanciere::class, inversedBy="operationFinanciereDon", cascade={"persist", "remove"})
     */
    private $operation;

    /**
     * @ORM\Column(type="date")
     */
    private $datelimitezakat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomdonataire(): ?string
    {
        return $this->nomdonataire;
    }

    public function setNomdonataire(string $nomdonataire): self
    {
        $this->nomdonataire = $nomdonataire;

        return $this;
    }

    public function getStipulation(): ?string
    {
        return $this->stipulation;
    }

    public function setStipulation(string $stipulation): self
    {
        $this->stipulation = $stipulation;

        return $this;
    }
    public function getCaisse(): ?Caisse
    {
        return $this->caisse;
    }

    public function setCaisse(?Caisse $caisse): self
    {
        $this->caisse = $caisse;

        return $this;
    }

    public function getOperation(): ?OperationFinanciere
    {
        return $this->operation;
    }

    public function setOperation(?OperationFinanciere $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    public function getDatelimitezakat(): ?\DateTimeInterface
    {
        return $this->datelimitezakat;
    }

    public function setDatelimitezakat(\DateTimeInterface $datelimitezakat): self
    {
        $this->datelimitezakat = $datelimitezakat;

        return $this;
    }
}
