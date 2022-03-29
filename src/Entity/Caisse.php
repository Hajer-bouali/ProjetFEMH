<?php

namespace App\Entity;

use App\Repository\CaisseRepository;
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
}
