<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReferentielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReferentielRepository::class)
 *  @ApiResource(
 *      denormalizationContext={"groups"={"groupcompetence:write"}},
 *     normalizationContext={"groups"={"referentiel:read"}},
 *     routePrefix="/admin",
 *     attributes={
 * "security"="is_granted('ROLE_admin')  or is_granted('ROLE_formateur') or is_granted('ROLE_cm')",
 * "security_message"="Ressource accessible" ,
 * },
 *   collectionOperations={
 *  "get_profilsorties"=
 *  {
 *      "method"="GET",
 *      "path"="/referentiels" ,
 *      "route_name"="getprofilsorties",
 *      "access_control_message"="Vous n'avez pas access à cette Ressource",
 *  },
 *        "post"={"path"="/referentiels"},
 *       "get_groupComp"={
 *              "method"="GET",
 *              "path"="/referentiels/groupeCompetences", "normalization_context"={"groups":"refcomp:read"}
 *          },
 *        "get_grpecompetence_by_id"={
 *         "method"="GET",
 *         "path"="/api/referentiels/{id1}/grpecompetences/{id2}", "normalization_context"={"groups":"comGroup:read"},
 *         "access_control"="(is_granted('ROLE_admin') or is_granted('ROLE_formateur') or is_granted('ROLE_apprenant'))",
 *         "access_control_message"="Vous n'avez pas access à cette Ressource",
 *         "route_name"="grpecompetence_by_id",
 * },
 *     },
 *      itemOperations={
 *     "get"={"path"="/referentiels/{id}", "normalization_context"={"groups":"referentiel:read"}},
 *     "put"={"path"="/referentiels/{id}"},
 *     }
 *     )
 */
class Referentiel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"referentiel:read", "groupcompetence:write"})
     * @Groups ({"read","promo:read","promo:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read", "groupcompetence:write"})
     * @Groups ({"read","promo:read","promo:write"})
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read", "groupcompetence:write"})
     * @Groups ({"read","promo:read","promo:write"})
     */
    private $presentation;



    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read", "groupcompetence:write"})
     * @Groups ({"read","promo:read","promo:write"})
     */
    private $criteresAdmission;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"referentiel:read", "groupcompetence:write"})
     * @Groups ({"read","promo:read","promo:write"})
     */
    private $criteresEvaluation;


    /**
     * @ORM\OneToMany(targetEntity=CompetencesValides::class, mappedBy="referentiel")
     */
    private $competencesValides;

    /**
     * @ORM\ManyToMany(targetEntity=Promo::class, mappedBy="referentiel",cascade = {"persist"})
     */
    private $promos;

    /**
     * @ORM\Column(type="blob", nullable=true)
     *  @Groups({"referentiel:read", "groupcompetence:write"})
     *
     */
    private $programme;

    /**
     * @ORM\ManyToMany(targetEntity=GroupeCompetences::class, inversedBy="referentiels", cascade = {"persist"})
     * @Groups({"referentiel:read","groupcompetence:write"})
     */
    private $groupeCompetences;

    public function __construct()
    {
        $this->groupeCompetences = new ArrayCollection();
        $this->competencesValides = new ArrayCollection();
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getCriteresAdmission(): ?string
    {
        return $this->criteresAdmission;
    }

    public function setCriteresAdmission(string $criteresAdmission): self
    {
        $this->criteresAdmission = $criteresAdmission;

        return $this;
    }

    public function getCriteresEvaluation(): ?string
    {
        return $this->criteresEvaluation;
    }

    public function setCriteresEvaluation(string $criteresEvaluation): self
    {
        $this->criteresEvaluation = $criteresEvaluation;

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
        }

        return $this;
    }

    public function removeCompetencesValide(CompetencesValides $competencesValide): self
    {
        if ($this->competencesValides->contains($competencesValide)) {
            $this->competencesValides->removeElement($competencesValide);
        }

        return $this;
    }

    /**
     * @return Collection|Promo[]
     */
    public function getPromos(): Collection
    {
        return $this->promos;
    }

    public function addPromo(Promo $promo): self
    {
        if (!$this->promos->contains($promo)) {
            $this->promos[] = $promo;
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->contains($promo)) {
            $this->promos->removeElement($promo);
        }

        return $this;
    }

    public function getProgramme()
    {
        if ($this->programme != null) {
            return base64_encode(stream_get_contents($this->programme, -1, 0));
        } else {
            return $this->programme;
        }
    }

    public function setProgramme($programme): self
    {
        $this->programme = $programme;

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
        }

        return $this;
    }

    public function removeGroupeCompetence(GroupeCompetences $groupeCompetence): self
    {
        $this->groupeCompetences->removeElement($groupeCompetence);

        return $this;
    }

}
