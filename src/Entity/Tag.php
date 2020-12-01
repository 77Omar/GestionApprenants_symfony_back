<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 * @UniqueEntity(
 * fields={"libelle"},
 * message="Le libelle existe déjà."
 * )
 * @ApiResource(
 *     normalizationContext={"groups"={"tagGroupe:read"}},
 *     routePrefix="/admin",
 *     attributes={
 * "security"="is_granted('ROLE_admin')  or is_granted('ROLE_formateur')",
 * "security_message"="Ressource accessible"  ,
 * },
 *   collectionOperations={
 *     "get"={"path"="/tags"},
 *      "post"={"path"="/tags"},
 *     },
 *      itemOperations={
 *     "get"={"path"="/tags/{id}"},
 *     "put"={"path"="/tags/{id}"},
 *     }
 * )
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * Groups({"tags:read", "tagsInGrpeTag:read", "tagGroupe:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tags:read", "tagsInGrpeTag:read", "tagGroupe:read"})
     */
    private $libelle;



    /**
     * @ORM\ManyToMany(targetEntity=GroupeTag::class, mappedBy="tags",cascade={"persist"})
     * @Groups({"tagGroupe:read"})
     */

    private $groupeTags;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Deleted=false;


    public function __construct()
    {
        $this->groupeTags = new ArrayCollection();
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
     * @return Collection|GroupeTag[]
     */
    public function getGroupeTags(): Collection
    {
        return $this->groupeTags;
    }

    public function addGroupeTag(GroupeTag $groupeTag): self
    {
        if (!$this->groupeTags->contains($groupeTag)) {
            $this->groupeTags[] = $groupeTag;
            $groupeTag->addTag($this);
        }

        return $this;
    }

    public function removeGroupeTag(GroupeTag $groupeTag): self
    {
        if ($this->groupeTags->contains($groupeTag)) {
            $this->groupeTags->removeElement($groupeTag);
            $groupeTag->removeTag($this);
        }

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->Deleted;
    }

    public function setDeleted(bool $Deleted): self
    {
        $this->Deleted = $Deleted;

        return $this;
    }

}
