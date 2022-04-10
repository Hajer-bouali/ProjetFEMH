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
     * @ORM\Column(type="date")
     */
    private $date;

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
     * @ORM\OneToMany(targetEntity=OperationFinanciereAide::class, mappedBy="evenement")
     */
    private $operationFinanciereAides;

    public function __construct()
    {
        $this->adherent = new ArrayCollection();
        $this->produit = new ArrayCollection();
        $this->operationFinanciereAides = new ArrayCollection();
    }

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

    /**
     * @return Collection|OperationFinanciereAide[]
     */
    public function getOperationFinanciereAides(): Collection
    {
        return $this->operationFinanciereAides;
    }

    public function addOperationFinanciereAide(OperationFinanciereAide $operationFinanciereAide): self
    {
        if (!$this->operationFinanciereAides->contains($operationFinanciereAide)) {
            $this->operationFinanciereAides[] = $operationFinanciereAide;
            $operationFinanciereAide->setEvenement($this);
        }

        return $this;
    }

    public function removeOperationFinanciereAide(OperationFinanciereAide $operationFinanciereAide): self
    {
        if ($this->operationFinanciereAides->removeElement($operationFinanciereAide)) {
            // set the owning side to null (unless already changed)
            if ($operationFinanciereAide->getEvenement() === $this) {
                $operationFinanciereAide->setEvenement(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->nom;
    }
}
