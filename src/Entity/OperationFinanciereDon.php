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
    private $typeadherent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stipulation;
    /**
     * @ORM\ManyToOne(targetEntity=Caisse::class, inversedBy="operationFinanciereDons")
     */
    private $caisse;

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

    public function getTypeadherent(): ?string
    {
        return $this->typeadherent;
    }

    public function setTypeadherent(string $typeadherent): self
    {
        $this->typeadherent = $typeadherent;

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
}
