<?php

namespace App\Entity;
use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdherentRepository::class)
 */
class Adherent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $cin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomconjoint;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cinconjoint;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etatcivil;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombrefamille;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logement;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $prixlocation;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $nombrechambre;

    /**
     * @ORM\Column(type="boolean")
     */
    private $electricite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $eau;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $installationnondisponible;

    /**
     * @ORM\Column(type="boolean")
     */
    private $handicap;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typehandicap;

    /**
     * @ORM\Column(type="boolean")
     */
    private $famillehandicap;

    /**
     * @ORM\Column(type="boolean")
     */
    private $maladiechronique;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typemaladiechronique;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $montantrevenu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $resume;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $demande;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $quienregistrefichier;




    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=PiecesJointes::class, mappedBy="attachement", orphanRemoval=true ,cascade={"persist"})
     */
    private $piecesJointes;

    /**
     * @ORM\ManyToMany(targetEntity=Evenement::class, mappedBy="adherent")
     */
    private $evenements;

    public function __construct()
    {
        $this->piecesJointes = new ArrayCollection();
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

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

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNomconjoint(): ?string
    {
        return $this->nomconjoint;
    }

    public function setNomconjoint(string $nomconjoint): self
    {
        $this->nomconjoint = $nomconjoint;

        return $this;
    }

    public function getCinconjoint(): ?string
    {
        return $this->cinconjoint;
    }

    public function setCinconjoint(string $cinconjoint): self
    {
        $this->cinconjoint = $cinconjoint;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEtatcivil(): ?string
    {
        return $this->etatcivil;
    }

    public function setEtatcivil(string $etatcivil): self
    {
        $this->etatcivil = $etatcivil;

        return $this;
    }

    public function getNombrefamille(): ?string
    {
        return $this->nombrefamille;
    }

    public function setNombrefamille(string $nombrefamille): self
    {
        $this->nombrefamille = $nombrefamille;

        return $this;
    }

    public function getLogement(): ?string
    {
        return $this->logement;
    }

    public function setLogement(string $logement): self
    {
        $this->logement = $logement;

        return $this;
    }

    public function getPrixlocation(): ?string
    {
        return $this->prixlocation;
    }

    public function setPrixlocation(string $prixlocation): self
    {
        $this->prixlocation = $prixlocation;

        return $this;
    }

    public function getNombrechambre(): ?string
    {
        return $this->nombrechambre;
    }

    public function setNombrechambre(string $nombrechambre): self
    {
        $this->nombrechambre = $nombrechambre;

        return $this;
    }

    public function getElectricite(): ?bool
    {
        return $this->electricite;
    }

    public function setElectricite(bool $electricite): self
    {
        $this->electricite = $electricite;

        return $this;
    }

    public function getEau(): ?string
    {
        return $this->eau;
    }

    public function setEau(string $eau): self
    {
        $this->eau = $eau;

        return $this;
    }

    public function getInstallationnondisponible(): ?string
    {
        return $this->installationnondisponible;
    }

    public function setInstallationnondisponible(string $installationnondisponible): self
    {
        $this->installationnondisponible = $installationnondisponible;

        return $this;
    }

    public function getHandicap(): ?bool
    {
        return $this->handicap;
    }

    public function setHandicap(bool $handicap): self
    {
        $this->handicap = $handicap;

        return $this;
    }

    public function getTypehandicap(): ?string
    {
        return $this->typehandicap;
    }

    public function setTypehandicap(string $typehandicap): self
    {
        $this->typehandicap = $typehandicap;

        return $this;
    }

    public function getFamillehandicap(): ?bool
    {
        return $this->famillehandicap;
    }

    public function setFamillehandicap(bool $famillehandicap): self
    {
        $this->famillehandicap = $famillehandicap;

        return $this;
    }

    public function getMaladiechronique(): ?bool
    {
        return $this->maladiechronique;
    }

    public function setMaladiechronique(bool $maladiechronique): self
    {
        $this->maladiechronique = $maladiechronique;

        return $this;
    }

    public function getTypemaladiechronique(): ?string
    {
        return $this->typemaladiechronique;
    }

    public function setTypemaladiechronique(string $typemaladiechronique): self
    {
        $this->typemaladiechronique = $typemaladiechronique;

        return $this;
    }

    public function getMontantrevenu(): ?string
    {
        return $this->montantrevenu;
    }

    public function setMontantrevenu(string $montantrevenu): self
    {
        $this->montantrevenu = $montantrevenu;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getDemande(): ?string
    {
        return $this->demande;
    }

    public function setDemande(string $demande): self
    {
        $this->demande = $demande;

        return $this;
    }

    public function getQuienregistrefichier(): ?string
    {
        return $this->quienregistrefichier;
    }

    public function setQuienregistrefichier(string $quienregistrefichier): self
    {
        $this->quienregistrefichier = $quienregistrefichier;

        return $this;
    }

   



    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection|PiecesJointes[]
     */
    public function getPiecesJointes(): Collection
    {
        return $this->piecesJointes;
    }

    public function addPiecesJointe(PiecesJointes $piecesJointe): self
    {
        if (!$this->piecesJointes->contains($piecesJointe)) {
            $this->piecesJointes[] = $piecesJointe;
            $piecesJointe->setAttachement($this);
        }

        return $this;
    }

    public function removePiecesJointe(PiecesJointes $piecesJointe): self
    {
        if ($this->piecesJointes->removeElement($piecesJointe)) {
            // set the owning side to null (unless already changed)
            if ($piecesJointe->getAttachement() === $this) {
                $piecesJointe->setAttachement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->addAdherent($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            $evenement->removeAdherent($this);
        }

        return $this;
    }

    public function __toString() {
        return $this->getNom() ? : 'Adherent';
    }
}