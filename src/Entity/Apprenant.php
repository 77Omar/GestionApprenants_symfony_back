<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;


    public function getId(): ?int
    {
        return $this->id;
    }

}
