<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $intitule;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
     * @ORM\Column(type="date")
     */
    private $dateExpiration;

    /**
     * @ORM\ManyToOne(targetEntity=TypeProduit::class,cascade={"persist"}, inversedBy="produit")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $typeProduit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $unite;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="produit")
     */
    private $stocks;

    /**
     * @ORM\OneToMany(targetEntity=FicheTechnique::class, mappedBy="produit")
     */
    private $ficheTechniques;

    public function __construct()
    {
        $this->ficheTechniques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
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

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(\DateTimeInterface $dateExpiration): self
    {
        $this->dateExpiration = $dateExpiration;

        return $this;
    }

    public function getTypeProduit(): ?TypeProduit
    {
        return $this->typeProduit;
    }

    public function setTypeProduit(?TypeProduit $typeProduit): self
    {
        $this->typeProduit = $typeProduit;

        return $this;
    }


    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(string $unite): self
    {
        $this->unite = $unite;

        return $this;
    }
    public function __toString() {
        return $this->intitule;
        ;
    }

    /**
     * @return Collection|Stock[]
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): self
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks[] = $stock;
            $stock->setProduit($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getproduit() === $this) {
                $stock->setproduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FicheTechnique>
     */
    public function getFicheTechniques(): Collection
    {
        return $this->ficheTechniques;
    }

    public function addFicheTechnique(FicheTechnique $ficheTechnique): self
    {
        if (!$this->ficheTechniques->contains($ficheTechnique)) {
            $this->ficheTechniques[] = $ficheTechnique;
            $ficheTechnique->setProduit($this);
        }

        return $this;
    }

    public function removeFicheTechnique(FicheTechnique $ficheTechnique): self
    {
        if ($this->ficheTechniques->removeElement($ficheTechnique)) {
            // set the owning side to null (unless already changed)
            if ($ficheTechnique->getProduit() === $this) {
                $ficheTechnique->setProduit(null);
            }
        }

        return $this;
    }

}
