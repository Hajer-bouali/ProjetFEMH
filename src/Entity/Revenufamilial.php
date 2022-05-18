<?php

namespace App\Entity;

use App\Repository\RevenufamilialRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RevenufamilialRepository::class)
 */
class Revenufamilial
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
    private $benificiaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $affairessociales;

    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typecarte;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numinscription;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="revenufamilial")
     */
    private $adherent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $regimesecuritesociale;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeregimesecuritesociale;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBenificiaire(): ?string
    {
        return $this->benificiaire;
    }

    public function setBenificiaire(string $benificiaire): self
    {
        $this->benificiaire = $benificiaire;

        return $this;
    }

    public function getAffairessociales(): ?string
    {
        return $this->affairessociales;
    }

    public function setAffairessociales(string $affairessociales): self
    {
        $this->affairessociales = $affairessociales;

        return $this;
    }



    public function getTypecarte(): ?string
    {
        return $this->typecarte;
    }

    public function setTypecarte(string $typecarte): self
    {
        $this->typecarte = $typecarte;

        return $this;
    }

    public function getNuminscription(): ?string
    {
        return $this->numinscription;
    }

    public function setNuminscription(string $numinscription): self
    {
        $this->numinscription = $numinscription;

        return $this;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): self
    {
        $this->adherent = $adherent;

        return $this;
    }

    public function getRegimesecuritesociale(): ?string
    {
        return $this->regimesecuritesociale;
    }

    public function setRegimesecuritesociale(string $regimesecuritesociale): self
    {
        $this->regimesecuritesociale = $regimesecuritesociale;

        return $this;
    }

    public function getTyperegimesecuritesociale(): ?string
    {
        return $this->typeregimesecuritesociale;
    }

    public function setTyperegimesecuritesociale(string $typeregimesecuritesociale): self
    {
        $this->typeregimesecuritesociale = $typeregimesecuritesociale;

        return $this;
    }
}
