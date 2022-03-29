<?php

namespace App\Entity;

use App\Repository\PiecesJointesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PiecesJointesRepository::class)
 */
class PiecesJointes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="piecesJointes")
     * @ORM\JoinColumn(nullable=false)
     */
    public $attachement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttachement(): ?Adherent
    {
        return $this->attachement;
    }

    public function setAttachement(?Adherent $attachement): self
    {
        $this->attachement = $attachement;

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
}
