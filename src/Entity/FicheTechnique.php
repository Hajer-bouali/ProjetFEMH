<?php

namespace App\Entity;

use App\Repository\FicheTechniqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FicheTechniqueRepository::class)
 */
class FicheTechnique
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
    private $quantite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $unite;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbstockproduit;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="ficheTechniques")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="ficheTechniques" ,cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $evenement;

    /**
     * @ORM\Column(type="boolean")
     */
    private $quantiteCalculee;

    public function __construct()
    {
        $this->setQuantiteCalculee(false);
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): self
    {
        $this->quantite = $quantite;

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

    public function getNbstockproduit(): ?int
    {
        return $this->nbstockproduit;
    }

    public function setNbstockproduit(int $nbstockproduit): self
    {
        $this->nbstockproduit = $nbstockproduit;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

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

    public function isQuantiteCalculee(): ?bool
    {
        return $this->quantiteCalculee;
    }

    public function setQuantiteCalculee(bool $quantiteCalculee): self
    {
        $this->quantiteCalculee = $quantiteCalculee;

        return $this;
    }
}
