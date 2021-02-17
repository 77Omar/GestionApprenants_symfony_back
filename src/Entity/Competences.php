<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CompetencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompetencesRepository::class)

 * @ApiResource(
 * denormalizationContext={"groups"={"competences:write"}},
 *    routePrefix="/admin",
 *     attributes={
 * "security"="is_granted('ROLE_admin')  or is_granted('ROLE_formateur') or is_granted('ROLE_cm')",
 * "security_message"="Ressource accessible"  ,
 * },
 *   collectionOperations={
 *     "get"={"path"="/competences", "normalization_context"={"groups":"competence:read"}},
 *      "post"={"path"="/competences"},
 *     },
 *     itemOperations={
 *     "get"={"path"="/competences/{id}", "normalization_context"={"groups":"competence:read"}},
 *       "put"={"path"="/competences/{id}"},
 *     }
 * )
 */


class Competences
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"compGroucomp:read","groupecomp:write", "refcomp:read", "comGroup:read"})
     * @Groups({"competences:write", "competence:read", "groupecomp:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255 )
     * @Groups({"groupComp:read", "compGroucomp:read","groupecomp:write", "refcomp:read", "competences:write", "comGroup:read"})
     * @Groups({"competences:write", "competence:read", "groupecomp:write"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetences::class, mappedBy="competences",cascade={"persist"})
     * @Groups({"competence:read", "competences:write"})
     */
    private $groupeCompetences;


    /**
     * @ORM\OneToMany(targetEntity=CompetencesValides::class, mappedBy="competences",cascade={"persist"})
     */
    private $competencesValides;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archived = false;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="competence",cascade={"persist"})
     * @Groups({"groupecompetence:read_All"})
     * @Groups({"competence:read", "competences:write"})
     */
    private $niveaux;


    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->competencesValides = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
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

    /**
     * @return Collection|GroupeCompetences[]
     */
    public function getGroupeCompetences(): Collection
    {
        return $this->groupeCompetences;
    }

    public function addGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if (!$this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences[] = $groupeCompetence;
            $groupeCompetence->addCompetence($this);
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        if ($this->groupeCompetences->contains($groupeCompetence)) {
            $this->groupeCompetences->removeElement($groupeCompetence);
            $groupeCompetence->removeCompetence($this);
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
            $competencesValide->setCompetences($this);
        }

        return $this;
    }

    public function removeCompetencesValide(CompetencesValides $competencesValide): self
    {
        if ($this->competencesValides->contains($competencesValide)) {
            $this->competencesValides->removeElement($competencesValide);
            // set the owning side to null (unless already changed)
            if ($competencesValide->getCompetences() === $this) {
                $competencesValide->setCompetences(null);
            }
        }

        return $this;
    }

    public function getArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveaux(): Collection
    {
        //$this->niveaux = new ArrayCollection();
        return $this->niveaux;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->setCompetence($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveaux->contains($niveau)) {
            $this->niveaux->removeElement($niveau);
            // set the owning side to null (unless already changed)
            if ($niveau->getCompetence() === $this) {
                $niveau->setCompetence(null);
            }
        }

        return $this;
    }
}
