<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeCompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeCompetencesRepository::class)
 * @UniqueEntity(
 * fields={"libelle"},
 * message="Le libelle existe déjà."
 * )
 *  @ApiResource(
 *   normalizationContext={"groups"={"groupComp:read"}},
 *     routePrefix="/admin",
 *     attributes={
 * "security"="is_granted('ROLE_admin')  or is_granted('ROLE_formateur') or is_granted('ROLE_cm')",
 * "security_message"="Ressource accessible"  ,
 * },
 *   collectionOperations={
 *     "get"={"path"="/groupeCompetence"},
 *     "post"={"path"="/groupeCompetence"},
 *     "get_groupComp"={
 *              "method"="GET",
 *              "path"="/groupeCompetence/{id}/competences"
 *          },
 *    "get_groupCompetence"={
 *              "method"="GET",
 *              "path"="/groupeCompetence/competences","normalization_context"={"groups":"compGroucomp:read"}
 *          },
 *     },
 *      itemOperations={
 *     "get"={"path"="/groupeCompetence/{id}"},
 *     "put"={"path"="/groupeCompetence/{id}"},
 *     }
 *     )
 */
class GroupeCompetences
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"compGroucomp:read", "compGroucomp:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"compGroucomp:read", "compGroucomp:read"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le descriptif est obligatoire")
     * @Groups({"compGroucomp:read", "compGroucomp:read"})
     */
    private $descriptif;

    /**
     * @ORM\ManyToMany(targetEntity=Competences::class, inversedBy="groupeCompetences",cascade={"persist"})
     * @Groups({"groupComp:read", "compGroucomp:read"})
     */
    private $competences;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, inversedBy="groupeCompetences",cascade={"persist"})
     */
    private $referentiel;



    public function __construct()
    {
        $this->creerCompetence = new ArrayCollection();
        $this->affecter = new ArrayCollection();
        $this->competences = new ArrayCollection();
        $this->referentiel = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * @return Collection|Competences[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competences $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
        }

        return $this;
    }

    public function removeCompetence(Competences $competence): self
    {
        if ($this->competences->contains($competence)) {
            $this->competences->removeElement($competence);
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
        if (!$this->$referentiel->contains($referentiel)) {
            $this->$referentiel[] = $referentiel;
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


}
