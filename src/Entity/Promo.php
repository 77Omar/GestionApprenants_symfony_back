<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PromoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PromoRepository::class)
 * @ApiResource(
 *     routePrefix="/admin",
 *     denormalizationContext={"groups"={"promo:write"}},
 *     normalizationContext={"groups"={"promo:read"}},
 * collectionOperations=
 *  {
 *    "get_promo"={
 *   "methods"="GET",
 *   "path"="/promo",
 *   "access_control"="(is_granted('ROLE_admin') or is_granted('ROLE_formateur') or is_granted('ROLE_cm'))",
 *   "access_control_message"="Vous n'avez pas access à cette Ressource",
 *   "route_name"="promo_liste",
 *   },
 *    "add_promo" = {
 *        "method"="POST",
 *         "path"="/promos",
 *          "access_control"="(is_granted('ROLE_admin') or is_granted('ROLE_formateur') or is_granted('ROLE_cm'))",
 *           "access_control_message"="Vous n'avez pas access à cette Ressource",
 *           },
 *     }
 *     );
 */
class Promo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read","promo:read","promo:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read","promo:read","promo:write"})
     */
    private $langue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read","promo:read","promo:write"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read","promo:read","promo:write"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read","promo:read","promo:write"})
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read","promo:read","promo:write"})
     */
    private $referenceAgate;

    /**
     * @ORM\Column(type="date")
     * @Groups({"read","promo:read","promo:write"})
     */
    private $dateDebut;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read","promo:read","promo:write"})
     */
    private $fabrique;

    /**
     * @ORM\Column(type="date")
     * @Groups({"read","promo:read", "promo:write"})
     */
    private $dateFin;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, inversedBy="promos",cascade={"persist"})
     */
    private $formateur;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, inversedBy="promos",cascade={"persist"})
     * @Groups ({"read", "promo:read","promo:write"})
     */
    private $referentiel;

    /**
     * @ORM\OneToMany(targetEntity=Apprenant::class, mappedBy="promo",cascade={"persist"})
     * @Groups ({"promo:read","promo:write"})
     */
    private $apprenant;

    /**
     * @ORM\ManyToMany(targetEntity=Briefs::class, mappedBy="promo",cascade={"persist"})
     */
    private $briefs;

    /**
     * @ORM\OneToMany(targetEntity=CompetencesValides::class, mappedBy="promo",cascade={"persist"})
     */
    private $competencesValides;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="promo", cascade={"remove"})
     */
    private $groupe;



    public function __construct()
    {
        $this->formateur = new ArrayCollection();
        $this->referentiel = new ArrayCollection();
        $this->apprenant = new ArrayCollection();
        $this->briefs = new ArrayCollection();
        $this->competencesValides = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): self
    {
        $this->langue = $langue;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getReferenceAgate(): ?string
    {
        return $this->referenceAgate;
    }

    public function setReferenceAgate(string $referenceAgate): self
    {
        $this->referenceAgate = $referenceAgate;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }


    public function getFabrique(): ?string
    {
        return $this->fabrique;
    }

    public function setFabrique(string $fabrique): self
    {
        $this->fabrique = $fabrique;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateur(): Collection
    {
        return $this->formateur;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateur->contains($formateur)) {
            $this->formateur[] = $formateur;
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateur->contains($formateur)) {
            $this->formateur->removeElement($formateur);
        }

        return $this;
    }

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentiel(): Collection
    {
        return $this->referentiel;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiel->contains($referentiel)) {
            $this->referentiel[] = $referentiel;
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiel->contains($referentiel)) {
            $this->referentiel->removeElement($referentiel);
        }

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenant(): Collection
    {
        return $this->apprenant;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenant->contains($apprenant)) {
            $this->apprenant[] = $apprenant;
            $apprenant->setPromo($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenant->contains($apprenant)) {
            $this->apprenant->removeElement($apprenant);
            // set the owning side to null (unless already changed)
            if ($apprenant->getPromo() === $this) {
                $apprenant->setPromo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Briefs[]
     */
    public function getBriefs(): Collection
    {
        return $this->briefs;
    }

    public function addBrief(Briefs $brief): self
    {
        if (!$this->briefs->contains($brief)) {
            $this->briefs[] = $brief;
            $brief->addPromo($this);
        }

        return $this;
    }

    public function removeBrief(Briefs $brief): self
    {
        if ($this->briefs->contains($brief)) {
            $this->briefs->removeElement($brief);
            $brief->removePromo($this);
        }

        return $this;
    }

    /**
     * @return Collection|CompetencesValides[]
     */
    public function getCompetencesValides(): Collection
    {
        return $this->competencesValides;
    }

    public function addCompetencesValide(CompetencesValides $competencesValide): self
    {
        if (!$this->competencesValides->contains($competencesValide)) {
            $this->competencesValides[] = $competencesValide;
            $competencesValide->setPromo($this);
        }

        return $this;
    }

    public function removeCompetencesValide(CompetencesValides $competencesValide): self
    {
        if ($this->competencesValides->contains($competencesValide)) {
            $this->competencesValides->removeElement($competencesValide);
            // set the owning side to null (unless already changed)
            if ($competencesValide->getPromo() === $this) {
                $competencesValide->setPromo(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|Groupe[]
     */
    public function getGroupe(): Collection
    {
        return $this->groupe;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupe->contains($groupe)) {
            $this->groupe[] = $groupe;
            $groupe->setPromo($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupe->contains($groupe)) {
            $this->groupe->removeElement($groupe);
            // set the owning side to null (unless already changed)
            if ($groupe->getPromo() === $this) {
                $groupe->setPromo(null);
            }
        }

        return $this;
    }


}
