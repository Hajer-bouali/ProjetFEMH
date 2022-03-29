<?php

namespace App\Entity;

use App\Repository\ReunionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReunionRepository::class)
 */
class Reunion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

  

    /**
     * @ORM\Column(type="text")
     */
    private $listemembre;

    /**
     * @ORM\OneToMany(targetEntity=Decision::class, mappedBy="reunion")
     */
    private $decisions;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function __construct()
    {
        $this->decisions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

   

    public function getListemembre(): ?string
    {
        return $this->listemembre;
    }

    public function setListemembre(string $listemembre): self
    {
        $this->listemembre = $listemembre;

        return $this;
    }

    /**
     * @return Collection|Decision[]
     */
    public function getDecisions(): Collection
    {
        return $this->decisions;
    }

    public function addDecision(Decision $decision): self
    {
        if (!$this->decisions->contains($decision)) {
            $this->decisions[] = $decision;
            $decision->setReunion($this);
        }

        return $this;
    }

    public function removeDecision(Decision $decision): self
    {
        if ($this->decisions->removeElement($decision)) {
            // set the owning side to null (unless already changed)
            if ($decision->getReunion() === $this) {
                $decision->setReunion(null);
            }
        }

        return $this;
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
}
