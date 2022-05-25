<?php
namespace App\Entity;

use App\Repository\BenificiaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BenificiaireRepository::class)
 */
class Benificiaire
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
    private $relationfamiliale;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     */
    private $datenaissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $niveauetude;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $metier;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="benificiaires",cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $adherent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelationfamiliale(): ?string
    {
        return $this->relationfamiliale;
    }

    public function setRelationfamiliale(string $relationfamiliale): self
    {
        $this->relationfamiliale = $relationfamiliale;

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

    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(\DateTimeInterface $datenaissance): self
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    public function getNiveauetude(): ?string
    {
        return $this->niveauetude;
    }

    public function setNiveauetude(string $niveauetude): self
    {
        $this->niveauetude = $niveauetude;

        return $this;
    }

    public function getMetier(): ?string
    {
        return $this->metier;
    }

    public function setMetier(string $metier): self
    {
        $this->metier = $metier;

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
    public function __toString()
    {
        return $this->getNom() ? : 'benificiaire';
    }
    
   /* public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }*/
}
