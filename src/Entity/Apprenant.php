<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 * @ApiResource(
 *   collectionOperations={
 *       "get"={"path"="/apprenants"},
 *     },
 *     itemOperations={
 *      "get"={"path"="/apprenants/{id}"},
 *     }
 *     )
 */
class Apprenant extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups ({"read", "group:read"})
     * @Groups ({"write","promo:read","promo:write"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups ({"read", "group:read"})
     * @Groups ({"write","promo:read","promo:write"})
     */
    private $adresse;

    /**
     * @ORM\ManyToMany(targetEntity=ProfilSorties::class, inversedBy="apprenants", cascade={"persist"})
     */
    private $profilSorties;
    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, inversedBy="apprenants", cascade={"persist"})
     */
    private $groupe;


    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="apprenant")
     */
    private $promo;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ({"read", "group:read"})
     * @Groups ({"write"})
     */
    private $en_attente = false;

    /**
     * @ORM\OneToMany(targetEntity=CompetencesValides::class, mappedBy="apprenant")
     * @Groups({"apprenant_competence:read","competences:read"})
     */
    private $competencesValides;

    public function __construct()
    {
        $this->profilSorties = new ArrayCollection();
        $this->groupe = new ArrayCollection();
        $this->competencesValides = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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


    /**
     * @return Collection|ProfilSorties[]
     */
    public function getProfilSorties(): Collection
    {
        return $this->profilSorties;
    }

    public function addProfilSorty(ProfilSorties $profilSorty): self
    {
        if (!$this->profilSorties->contains($profilSorty)) {
            $this->profilSorties[] = $profilSorty;
        }

        return $this;
    }

    public function removeProfilSorty(ProfilSorties $profilSorty): self
    {
        if ($this->profilSorties->contains($profilSorty)) {
            $this->profilSorties->removeElement($profilSorty);
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
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupe->contains($groupe)) {
            $this->groupe->removeElement($groupe);
        }

        return $this;
    }

    public function getPromo(): ?Promo
    {
        return $this->promo;
    }

    public function setPromo(?Promo $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    public function getEnAttente(): ?bool
    {
        return $this->en_attente;
    }

    public function setEnAttente(bool $en_attente): self
    {
        $this->en_attente = $en_attente;

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
            $competencesValide->setApprenant($this);
        }

        return $this;
    }

    public function removeCompetencesValide(CompetencesValides $competencesValide): self
    {
        if ($this->competencesValides->contains($competencesValide)) {
            $this->competencesValides->removeElement($competencesValide);
            // set the owning side to null (unless already changed)
            if ($competencesValide->getApprenant() === $this) {
                $competencesValide->setApprenant(null);
            }
        }

        return $this;
    }


}

