<?php

namespace App\Entity;

use App\Repository\OperationStockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OperationStockRepository::class)
 */
class OperationStock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Evenement::class, inversedBy="operationStocks")
     */
    private $evenement;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datelimitezakat;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $responsable;

    /**
     * @ORM\OneToMany(targetEntity=Stock::class, mappedBy="operationStock")
     */
    private $stock;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomdonataire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stipulation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeoperation;


    public function __construct()
    {
        $this->evenement = new ArrayCollection();
        $this->stock = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvenement(): Collection
    {
        return $this->evenement;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenement->contains($evenement)) {
            $this->evenement[] = $evenement;
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        $this->evenement->removeElement($evenement);

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
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
    /**
     * @return Collection|Stock[]
     */
    public function getStock(): Collection
    {
        return $this->stock;
    }

    public function addStock(Stock $stock): self
    {
        if (!$this->stock->contains($stock)) {
            $this->stock[] = $stock;
            $stock->setOperationStock($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stock->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getOperationStock() === $this) {
                $stock->setOperationStock(null);
            }
        }

        return $this;
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
}
