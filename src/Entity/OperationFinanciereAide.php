<?php

namespace App\Entity;

use App\Repository\OperationFinanciereAideRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OperationFinanciereAideRepository::class)
 */
class OperationFinanciereAide
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=OperationFinanciere::class, inversedBy="operationFinanciereAide", cascade={"persist", "remove"})
     */
    private $operation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOperation(): ?OperationFinanciere
    {
        return $this->operation;
    }

    public function setOperation(?OperationFinanciere $operation): self
    {
        $this->operation = $operation;

        return $this;
    }
}
