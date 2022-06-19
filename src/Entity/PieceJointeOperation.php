<?php

namespace App\Entity;

use App\Repository\PieceJointeOperationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PieceJointeOperationRepository::class)
 */
class PieceJointeOperation
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
     * @ORM\ManyToOne(targetEntity=OperationFinanciere::class, inversedBy="pieceJointeOperations")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $operationfinanciere;

 

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

    public function getOperationfinanciere(): ?OperationFinanciere
    {
        return $this->operationfinanciere;
    }

    public function setOperationfinanciere(?OperationFinanciere $operationfinanciere): self
    {
        $this->operationfinanciere = $operationfinanciere;

        return $this;
    }

  
}
