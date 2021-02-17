<?php

namespace App\Entity;

use App\Entity\Promo;
use DateTimeInterface;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 *  @ApiResource(
 *  attributes ={
 *    "security"="is_granted('ROLE_admin')  or is_granted('ROLE_formateur') or is_granted('ROLE_cm')",
 *    "security_message"="Ressource accessible"  ,
 *  "normalization_context"={"groups"={"read"}},
 *  "denormalization_Context"={"groups"={"write"}},
 * },
 *     collectionOperations={
 *      "get"={"path"="/groupes"},
 *      "post"={"path"="/groupes"},
 *       "get_group"={
 *              "method"="GET",
 *              "path"="/groupes/apprenants","normalization_context"={"groups":"group:read"}
 *          },
 *     }
 * );
 */
class Groupe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Promo::class, inversedBy="groupe", cascade={"persist"})
     * @Groups({"read"})
     */
    private $promo;

    /**
     * @ORM\ManyToMany(targetEntity=Formateur::class, mappedBy="groupe")
     * @Groups({"read"})
     * @Groups ({"write"})
     */
    private $formateurs;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, mappedBy="groupe", cascade={"persist"})
     * @Groups({"read", "group:read"})
     * @Groups ({"write"})
     */
    private $apprenants;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDeleted= false;


    public function __construct()
    {
        $this->formateurs = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    /**
     * @return Collection|Formateur[]
     */
    public function getFormateurs(): Collection
    {
        return $this->formateurs;
    }

    public function addFormateur(Formateur $formateur): self
    {
        if (!$this->formateurs->contains($formateur)) {
            $this->formateurs[] = $formateur;
            $formateur->addGroupe($this);
        }

        return $this;
    }

    public function removeFormateur(Formateur $formateur): self
    {
        if ($this->formateurs->contains($formateur)) {
            $this->formateurs->removeElement($formateur);
            $formateur->removeGroupe($this);
        }

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->addGroupe($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->contains($apprenant)) {
            $this->apprenants->removeElement($apprenant);
            $apprenant->removeGroupe($this);
        }

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

}
