<?php

namespace App\Entity;

use App\Repository\TypeCaisseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeCaisseRepository::class)
 */
class TypeCaisse
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Caisse::class, mappedBy="typeCaisse")
     */
    private $caisses;

    public function __construct()
    {
        $this->caisses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|Caisse[]
     */
    public function getCaisses(): Collection
    {
        return $this->caisses;
    }

    public function addCaiss(Caisse $caiss): self
    {
        if (!$this->caisses->contains($caiss)) {
            $this->caisses[] = $caiss;
            $caiss->setTypeCaisse($this);
        }

        return $this;
    }

    public function removeCaiss(Caisse $caiss): self
    {
        if ($this->caisses->removeElement($caiss)) {
            // set the owning side to null (unless already changed)
            if ($caiss->getTypeCaisse() === $this) {
                $caiss->setTypeCaisse(null);
            }
        }

        return $this;
    }
}
