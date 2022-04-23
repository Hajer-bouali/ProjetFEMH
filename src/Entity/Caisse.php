<?php

namespace App\Entity;

use App\Repository\CaisseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CaisseRepository::class)
 */
class Caisse
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
    private $intitule;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=TypeCaisse::class, inversedBy="caisses")
     */
    private $typeCaisse;

    /**
     * @ORM\OneToMany(targetEntity=OperationFinanciere::class, mappedBy="caisse")
     */
    private $operationFinancieres;

    public function __construct()
    {
        $this->operationFinancieres = new ArrayCollection();
    }

    
   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

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

    public function getTypeCaisse(): ?TypeCaisse
    {
        return $this->typeCaisse;
    }

    public function setTypeCaisse(?TypeCaisse $typeCaisse): self
    {
        $this->typeCaisse = $typeCaisse;

        return $this;
    }

    public function __toString() {
        return $this->intitule;
    }

    /**
     * @return Collection|OperationFinanciere[]
     */
    public function getOperationFinancieres(): Collection
    {
        return $this->operationFinancieres;
    }

    public function addOperationFinanciere(OperationFinanciere $operationFinanciere): self
    {
        if (!$this->operationFinancieres->contains($operationFinanciere)) {
            $this->operationFinancieres[] = $operationFinanciere;
            $operationFinanciere->setCaisse($this);
        }

        return $this;
    }

    public function removeOperationFinanciere(OperationFinanciere $operationFinanciere): self
    {
        if ($this->operationFinancieres->removeElement($operationFinanciere)) {
            // set the owning side to null (unless already changed)
            if ($operationFinanciere->getCaisse() === $this) {
                $operationFinanciere->setCaisse(null);
            }
        }

        return $this;
    }
}
