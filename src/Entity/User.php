<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *@ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *@ORM\InheritanceType("JOINED")
 *@ORM\DiscriminatorColumn(name="type",  type="string")
 *@ORM\DiscriminatorMap({"Admin" = "Admin", "admin"="User", "apprenant" = "Apprenant", "formteur" = "Formateur", "cm" ="Cm"})
 *  @ApiResource(
 *     routePrefix="/admin",
 *     attributes={
 * "security"="is_granted('ROLE_admin')",
 * "security_message"="Ressource accessible que par l'Admin"
 * },
 *     normalizationContext={"groups"={"userP:read"}},
 *     collectionOperations={
 *     "get"={"path"="/users", "normalization_context"={"groups"={"user:read"}}},
 *      "post"={"path"="/users","denormalization_context"={"groups"={"u:write"}}},
 *
 *     },
 *      itemOperations={
 *     "get"={"path"="/users/{id}","normalization_context"={"groups"={"user:read"}}},
 *     "put"={"path"="/users/{id}"},
 *     "delete"={"path"="/users/{id}","denormalization_context"={"groups"={"user:write"}}},
 *     }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"statut"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"user:read"})
     *
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Le email est obligatoire")
     *  @Groups ({"user:read","u:write","user:write","userP:read"})
     */
    protected $email;

    /**
     * @ORM\Column(type="json")
     * @Groups ({"user:read"})
     */
    protected $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Le password est obligatoire")
     * @Groups ({"user:read","u:write","user:write","userP:read"})
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le firstName est obligatoire")
     * @Groups ({"user:read","u:write","user:write","userP:read"})
     *
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le lastName est obligatoire")
     *  @Groups ({"user:read","u:write","user:write","userP:read"})
     */
    protected $lastName;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @Assert\NotBlank(message="Le Profil est obligatoire")
     * @Groups ({"user:read","u:write","user:write","userP:read"})
     *
     */
    protected $profil;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ({"user:read"})
     */
    private $statut=false;

    /**
     * @ORM\Column(type="blob")
     * @Groups ({"user:read","u:write","user:write","userP:read"})
     */
    private $avatar;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return ucfirst($this->firstName);
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return ucfirst($this->lastName);
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
    public function getProfil(): ?Profil
    {
        return $this->profil;
    }
    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;
        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

  /*  public function getAvatar()
    {
        return base64_encode()
        return base64_encode(stream_get_contents($this->avatar));
        //stream_get_contents($this->avatar, -1, 0)
    }*/

    public function getAvatar()
    {
        if ($this->avatar != null) {
            return base64_encode(stream_get_contents($this->avatar, -1, 0));
        } else {
            return $this->avatar;
        }
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
