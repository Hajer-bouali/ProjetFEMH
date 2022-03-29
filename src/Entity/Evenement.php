<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToMany(targetEntity=Adherent::class, inversedBy="evenements")
     */
    private $adherent;

    /**
     * @ORM\Column(type="float")
     */
    private $quantite;

    /**
     * @ORM\Column(type="float")
     */
    private $montent;

    /**
     * @ORM\Column(type="float")
     */
    private $bondachat;

    /**
     * @ORM\Column(type="float")
     */
    private $quantiteviande;

    /**
     * @ORM\Column(type="float")
     */
    private $quantitelaine;

    /**
     * @ORM\Column(type="integer")
     */
    private $numerodossier;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $beneficiairecouche;

    /**
     * @ORM\Column(type="float")
     */
    private $quantitecouche;

    /**
     * @ORM\Column(type="float")
     */
    private $taillecouche;

    /**
     * @ORM\Column(type="float")
     */
    private $telbeneficiairecouche;

    /**
     * @ORM\Column(type="float")
     */
    private $cinbeneficiairecouche;

    /**
     * @ORM\ManyToOne(targetEntity=TypeEvenement::class, inversedBy="evenement")
     */
    private $typeEvenement;

    public function __construct()
    {
        $this->adherent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Adherent[]
     */
    public function getAdherent(): Collection
    {
        return $this->adherent;
    }

    public function addAdherent(Adherent $adherent): self
    {
        if (!$this->adherent->contains($adherent)) {
            $this->adherent[] = $adherent;
        }

        return $this;
    }

    public function removeAdherent(Adherent $adherent): self
    {
        $this->adherent->removeElement($adherent);

        return $this;
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

    public function getMontent(): ?float
    {
        return $this->montent;
    }

    public function setMontent(float $montent): self
    {
        $this->montent = $montent;

        return $this;
    }

    public function getBondachat(): ?float
    {
        return $this->bondachat;
    }

    public function setBondachat(float $bondachat): self
    {
        $this->bondachat = $bondachat;

        return $this;
    }

    public function getQuantiteviande(): ?float
    {
        return $this->quantiteviande;
    }

    public function setQuantiteviande(float $quantiteviande): self
    {
        $this->quantiteviande = $quantiteviande;

        return $this;
    }

    public function getQuantitelaine(): ?float
    {
        return $this->quantitelaine;
    }

    public function setQuantitelaine(float $quantitelaine): self
    {
        $this->quantitelaine = $quantitelaine;

        return $this;
    }

    public function getBondereception(): ?string
    {
        return $this->bondereception;
    }

    public function setBondereception(string $bondereception): self
    {
        $this->bondereception = $bondereception;

        return $this;
    }

    public function getNumerodossier(): ?int
    {
        return $this->numerodossier;
    }

    public function setNumerodossier(int $numerodossier): self
    {
        $this->numerodossier = $numerodossier;

        return $this;
    }

    public function getBeneficiairecouche(): ?string
    {
        return $this->beneficiairecouche;
    }

    public function setBeneficiairecouche(string $beneficiairecouche): self
    {
        $this->beneficiairecouche = $beneficiairecouche;

        return $this;
    }

    public function getQuantitecouche(): ?float
    {
        return $this->quantitecouche;
    }

    public function setQuantitecouche(float $quantitecouche): self
    {
        $this->quantitecouche = $quantitecouche;

        return $this;
    }

    public function getTaillecouche(): ?float
    {
        return $this->taillecouche;
    }

    public function setTaillecouche(float $taillecouche): self
    {
        $this->taillecouche = $taillecouche;

        return $this;
    }

    public function getTelbeneficiairecouche(): ?float
    {
        return $this->telbeneficiairecouche;
    }

    public function setTelbeneficiairecouche(float $telbeneficiairecouche): self
    {
        $this->telbeneficiairecouche = $telbeneficiairecouche;

        return $this;
    }

    public function getCinbeneficiairecouche(): ?float
    {
        return $this->cinbeneficiairecouche;
    }

    public function setCinbeneficiairecouche(float $cinbeneficiairecouche): self
    {
        $this->cinbeneficiairecouche = $cinbeneficiairecouche;

        return $this;
    }

    public function getTypeEvenement(): ?TypeEvenement
    {
        return $this->typeEvenement;
    }

    public function setTypeEvenement(?TypeEvenement $typeEvenement): self
    {
        $this->typeEvenement = $typeEvenement;

        return $this;
    }
}
