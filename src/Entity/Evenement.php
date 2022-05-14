<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToMany(targetEntity=Adherent::class, inversedBy="evenements")
     */
    private $adherent;



    /**
     * @ORM\ManyToOne(targetEntity=TypeEvenement::class, inversedBy="evenement")
     */
    private $typeEvenement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity=Produit::class, inversedBy="evenements")
     */
    private $produit;

    /**
     * @ORM\Column(type="date")
     */
    private $datedebut;

    /**
     * @ORM\OneToMany(targetEntity=OperationFinanciere::class, mappedBy="evenement")
     */
    private $operationFinancieres;

    /**
     * @ORM\ManyToMany(targetEntity=OperationStock::class, mappedBy="evenement")
     */
    private $operationStocks;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datefin;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montantunitaire;


    public function __construct()
    {
        $this->adherent = new ArrayCollection();
        $this->produit = new ArrayCollection();
        $this->operationFinancieres = new ArrayCollection();
        $this->operationStocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return Collection|Adherent[]
     */
    public function getAdherent(): Collection
    {
        return $this->adherent;
    }

    public function addAdherent(Adherent $adherent): self
    {
        if (!$this->adherent->contains($adherent)) {
            $this->adherent[] = $adherent;
        }

        return $this;
    }

    public function removeAdherent(Adherent $adherent): self
    {
        $this->adherent->removeElement($adherent);

        return $this;
    }


    public function getTypeEvenement(): ?TypeEvenement
    {
        return $this->typeEvenement;
    }

    public function setTypeEvenement(?TypeEvenement $typeEvenement): self
    {
        $this->typeEvenement = $typeEvenement;

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

    /**
     * @return Collection<int, Produit>
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produit->contains($produit)) {
            $this->produit[] = $produit;
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        $this->produit->removeElement($produit);

        return $this;
    }


   

  
    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }
    
    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }


    public function __toString() {
        return $this->nom;
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
            $operationFinanciere->setEvenement($this);
        }

        return $this;
    }

    public function removeOperationFinanciere(OperationFinanciere $operationFinanciere): self
    {
        if ($this->operationFinancieres->removeElement($operationFinanciere)) {
            // set the owning side to null (unless already changed)
            if ($operationFinanciere->getEvenement() === $this) {
                $operationFinanciere->setEvenement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OperationStock[]
     */
    public function getOperationStocks(): Collection
    {
        return $this->operationStocks;
    }

    public function addOperationStock(OperationStock $operationStock): self
    {
        if (!$this->operationStocks->contains($operationStock)) {
            $this->operationStocks[] = $operationStock;
            $operationStock->addEvenement($this);
        }

        return $this;
    }

    public function removeOperationStock(OperationStock $operationStock): self
    {
        if ($this->operationStocks->removeElement($operationStock)) {
            $operationStock->removeEvenement($this);
        }

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getMontantunitaire(): ?float
    {
        return $this->montantunitaire;
    }

    public function setMontantunitaire(float $montantunitaire): self
    {
        $this->montantunitaire = $montantunitaire;

        return $this;
    }
}
