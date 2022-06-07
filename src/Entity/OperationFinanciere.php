<?php

namespace App\Entity;

use App\Repository\OperationFinanciereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", length=255, nullable=true)
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
     * @ORM\Column(type="string", length=255)
     */
    private $responsable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stipulation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeoperation;

    /**
     * @ORM\ManyToOne(targetEntity=Caisse::class, inversedBy="operationFinancieres")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $caisse;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="operationFinancieres")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $evenement;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datelimitezakat;

    /**
     * @ORM\OneToMany(targetEntity=PieceJointeOperation::class, mappedBy="operationfinanciere", cascade={"persist"})
     */
    private $pieceJointeOperations;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    public function __construct()
    {
        $this->pieceJointeOperations = new ArrayCollection();
    }

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

    public function getStipulation(): ?string
    {
        return $this->stipulation;
    }

    public function setStipulation(string $stipulation): self
    {
        $this->stipulation = $stipulation;

        return $this;
    }

    public function getTypeoperation(): ?string
    {
        return $this->typeoperation;
    }

    public function setTypeoperation(string $typeoperation): self
    {
        $this->typeoperation = $typeoperation;

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

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }

    public function getDatelimitezakat(): ?\DateTimeInterface
    {
        return $this->datelimitezakat;
    }

    public function setDatelimitezakat(?\DateTimeInterface $datelimitezakat): self
    {
        $this->datelimitezakat = $datelimitezakat;

        return $this;
    }

    /**
     * @return Collection|PieceJointeOperation[]
     */
    public function getPieceJointeOperations(): Collection
    {
        return $this->pieceJointeOperations;
    }

    public function addPieceJointeOperation(PieceJointeOperation $pieceJointeOperation): self
    {
        if (!$this->pieceJointeOperations->contains($pieceJointeOperation)) {
            $this->pieceJointeOperations[] = $pieceJointeOperation;
            $pieceJointeOperation->setOperationfinanciere($this);
        }

        return $this;
    }

    public function removePieceJointeOperation(PieceJointeOperation $pieceJointeOperation): self
    {
        if ($this->pieceJointeOperations->removeElement($pieceJointeOperation)) {
            // set the owning side to null (unless already changed)
            if ($pieceJointeOperation->getOperationfinanciere() === $this) {
                $pieceJointeOperation->setOperationfinanciere(null);
            }
        }

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

    public function toArray() {
        return [
            'aideOUdon'=> $this->getTypeoperation(),
            'montant' => $this->getMontant(),
            'evenement' => $this->getEvenement()->getNom()
        ];
    }


}
