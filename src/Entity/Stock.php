<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockRepository::class)
 */
class Stock
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
    private $quantite =1;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="stocks")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity=OperationStock::class, inversedBy="stock")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $operationStock;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $unite;

    /**
     * @ORM\Column(type="float")
     */
    private $prixunitaire;

    public function __construct()
    {
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

    public function getProduit(): ? Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): self
    {
        $this->produit = $produit;

        return $this;
    }

    public function getOperationStock(): ?OperationStock
    {
        return $this->operationStock;
    }

    public function setOperationStock(?OperationStock $operationStock): self
    {
        $this->operationStock = $operationStock;

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

    public function getPrixunitaire(): ?float
    {
        return $this->prixunitaire;
    }

    public function setPrixunitaire(float $prixunitaire): self
    {
        $this->prixunitaire = $prixunitaire;

        return $this;
    }

}
