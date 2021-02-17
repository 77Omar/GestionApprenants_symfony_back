<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=FormateurRepository::class)
 *  @ApiResource(
 *   collectionOperations={
 *       "get"={"path"="/formateurs"},
 *     },
 *     itemOperations={
 *      "get"={"path"="/formateurs/{id}"},
 *     }
 *     )
 */
class Formateur extends User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups ({"read"})
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity=Promo::class, mappedBy="formateur")
     *
     */
    private $promos;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, inversedBy="formateurs")
     */
    private $groupe;

    /**
     * @ORM\Column(type="integer",nullable=true)
     * @Groups ({"read","group:read"})
     * @Groups ({"write"})
     */
    private $telephone;


    public function __construct()
    {
        $this->promos = new ArrayCollection();
        $this->groupe = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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
            $promo->addFormateur($this);
        }

        return $this;
    }

    public function removePromo(Promo $promo): self
    {
        if ($this->promos->contains($promo)) {
            $this->promos->removeElement($promo);
            $promo->removeFormateur($this);
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

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

}

