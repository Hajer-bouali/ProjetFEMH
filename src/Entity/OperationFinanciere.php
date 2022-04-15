<?php

namespace App\Entity;

use App\Repository\OperationFinanciereRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OperationFinanciereRepository::class)
 */
class OperationFinanciere
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modepaiement;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $responsable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

   
    /**
     * @ORM\OneToOne(targetEntity=OperationFinanciereAide::class, mappedBy="operation", cascade={"persist", "remove"})
     */
    private $operationFinanciereAide;

    /**
     * @ORM\OneToOne(targetEntity=OperationFinanciereDon::class, mappedBy="operation", cascade={"persist", "remove"})
     */
    private $operationFinanciereDon;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="operationFinancieres")
     */
    private $evenement;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getModepaiement(): ?string
    {
        return $this->modepaiement;
    }

    public function setModepaiement(string $modepaiement): self
    {
        $this->modepaiement = $modepaiement;

        return $this;
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

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(string $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

   
    

    public function getOperationFinanciereAide(): ?OperationFinanciereAide
    {
        return $this->operationFinanciereAide;
    }

    public function setOperationFinanciereAide(?OperationFinanciereAide $operationFinanciereAide): self
    {
        // unset the owning side of the relation if necessary
        if ($operationFinanciereAide === null && $this->operationFinanciereAide !== null) {
            $this->operationFinanciereAide->setOperation(null);
        }

        // set the owning side of the relation if necessary
        if ($operationFinanciereAide !== null && $operationFinanciereAide->getOperation() !== $this) {
            $operationFinanciereAide->setOperation($this);
        }

        $this->operationFinanciereAide = $operationFinanciereAide;

        return $this;
    }

    public function getOperationFinanciereDon(): ?OperationFinanciereDon
    {
        return $this->operationFinanciereDon;
    }

    public function setOperationFinanciereDon(?OperationFinanciereDon $operationFinanciereDon): self
    {
        // unset the owning side of the relation if necessary
        if ($operationFinanciereDon === null && $this->operationFinanciereDon !== null) {
            $this->operationFinanciereDon->setOperation(null);
        }

        // set the owning side of the relation if necessary
        if ($operationFinanciereDon !== null && $operationFinanciereDon->getOperation() !== $this) {
            $operationFinanciereDon->setOperation($this);
        }

        $this->operationFinanciereDon = $operationFinanciereDon;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }
}
