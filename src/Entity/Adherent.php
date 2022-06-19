<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AdherentRepository;
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
     * @ORM\Column(type="decimal", precision=10, scale=0 , nullable=true)
     */
    private $prixlocation;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $nombrechambre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $electricite;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $eau;

    /**
     * @ORM\Column(type="boolean")
     */
    private $handicap;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $typehandicap;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $maladiechronique;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=PiecesJointes::class, mappedBy="attachement", orphanRemoval=true ,cascade={"persist"})
     * @ORM\JoinTable(name="")
     */
    private $piecesJointes;

    /**
     * @ORM\ManyToMany(targetEntity=Evenement::class, mappedBy="adherent")
     */
    private $evenements;

    /**

     * @ORM\Column(type="string", length=255)
     */
    private $etatreunion;

    /**
     * @ORM\OneToMany(targetEntity=Benificiaire::class, mappedBy="adherent")
     */
    private $benificiaires;

    /**
     * @ORM\ManyToMany(targetEntity=Typeadherent::class, inversedBy="adherents")
     */
    private $typeadherent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $responsable;

    /**
     * @ORM\OneToMany(targetEntity=Revenufamilial::class, mappedBy="adherent")
     */
    private $revenufamilial;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    public function __construct()
    {
        $this->piecesJointes = new ArrayCollection();
        $this->evenements = new ArrayCollection();
        $this->benificiaires = new ArrayCollection();
        $this->typeadherent = new ArrayCollection();
        $this->revenufamilial = new ArrayCollection();
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

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

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

    public function getElectricite(): ?string
    {
        return $this->electricite;
    }

    public function setElectricite(string $electricite): self
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


    public function getEtatreunion(): ?string
    {
        return $this->etatreunion;
    }

    public function setEtatreunion(string $etatreunion): self
    {
        $this->etatreunion = $etatreunion;
        return $this;
    }
    /**
     * @return Collection<int, Benificiaire>
     **/

    public function getBenificiaires(): Collection
    {
        return $this->benificiaires;
    }

    public function addBenificiaire(Benificiaire $benificiaire): self
    {
        if (!$this->benificiaires->contains($benificiaire)) {
            $this->benificiaires[] = $benificiaire;
            $benificiaire->setAdherent($this);
        }

        return $this;
    }

    public function removeBenificiaire(Benificiaire $benificiaire): self
    {
        if ($this->benificiaires->removeElement($benificiaire)) {
            // set the owning side to null (unless already changed)
            if ($benificiaire->getAdherent() === $this) {
                $benificiaire->setAdherent(null);
            }
        }


        return $this;
    }

    /**
     * @return Collection<int, Typeadherent>
     */
    public function getTypeadherent(): Collection
    {
        return $this->typeadherent;
    }

    public function addTypeadherent(Typeadherent $typeadherent): self
    {
        if (!$this->typeadherent->contains($typeadherent)) {
            $this->typeadherent[] = $typeadherent;
        }

        return $this;
    }

    public function removeTypeadherent(Typeadherent $typeadherent): self
    {
        $this->typeadherent->removeElement($typeadherent);

        return $this;
    }

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(string $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * @return Collection|revenufamilial[]
     */
    public function getRevenufamilial(): Collection
    {
        return $this->revenufamilial;
    }

    public function addRevenufamilial(revenufamilial $revenufamilial): self
    {
        if (!$this->revenufamilial->contains($revenufamilial)) {
            $this->revenufamilial[] = $revenufamilial;
            $revenufamilial->setAdherent($this);
        }

        return $this;
    }

    public function removeRevenufamilial(revenufamilial $revenufamilial): self
    {
        if ($this->revenufamilial->removeElement($revenufamilial)) {
            // set the owning side to null (unless already changed)
            if ($revenufamilial->getAdherent() === $this) {
                $revenufamilial->setAdherent(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

  
   
}