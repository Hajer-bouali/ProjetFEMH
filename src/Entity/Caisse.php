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
    private $montant=0;

    /**
     * @ORM\ManyToOne(targetEntity=TypeCaisse::class, inversedBy="caisses")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $typeCaisse;

    /**
     * @ORM\OneToMany(targetEntity=OperationFinanciere::class, mappedBy="caisse") 
     */
    private $operationFinancieres;

    /**
     * @ORM\OneToMany(targetEntity=Evenement::class, mappedBy="caisse")
     */
    private $evenements;

    public function __construct()
    {
        $this->operationFinancieres = new ArrayCollection();
        $this->evenements = new ArrayCollection();
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

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->setCaisse($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getCaisse() === $this) {
                $evenement->setCaisse(null);
            }
        }

        return $this;
    }
}
