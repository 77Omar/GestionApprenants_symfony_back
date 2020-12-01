<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GroupeTagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=GroupeTagRepository::class)
 * @UniqueEntity(
 * fields={"libelle"},
 * message="Le libelle existe déjà."
 * )
 *  @ApiResource(
 *     normalizationContext={"groups"={"tags:read"}},
 *     routePrefix="/admin",
 *     attributes={
 * "security"="is_granted('ROLE_admin')  or is_granted('ROLE_formateur')",
 * "security_message"="Ressource accessible"  ,
 * },
 *   collectionOperations={
 *     "get"={"path"="/groupstags"},
 *      "post"={"path"="/groupstags"},
 *     "get_groupstag"={
 *              "method"="GET",
 *              "path"="/groupstags/{id}/tags"
 *          },
 *     },
 *      itemOperations={
 *     "get"={"path"="/groupstags/{id}"},
 *     "put"={"path"="/groupstags/{id}"},
 *     }
 * )
 */
class GroupeTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"tagGroupe:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"tagGroupe:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="groupeTags",cascade={"persist"})
     * @Groups({"tags:read", "tagsInGrpeTag:read"})
     */
    private $tags;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archived=false;


    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
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
}
