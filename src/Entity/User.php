<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *@ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *@ORM\InheritanceType("JOINED")
 *@ORM\DiscriminatorColumn(name="type",  type="string")
 *@ORM\DiscriminatorMap({"admin" ="User", "apprenant" = "Apprenant", "formteur" = "Formateur", "cm" ="Cm"})
 * * @ApiResource(
 *     routePrefix="/admin",
 *     attributes={
 * "security"="is_granted('ROLE_admin')",
 * "security_message"="Ressource accessible que par l'Admin"
 * },
 *     collectionOperations={
 *     "get"={"path"="/users"},
 *      "post"={"path"="/users"},
 *     },
 *      itemOperations={
 *     "get"={"path"="/users/{id}"},
 *     "put"={"path"="/users/{id}"},
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
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $lastName;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     */
    protected $profil;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut=false;

    /**
     * @ORM\Column(type="blob")
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
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
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

    public function getAvatar()
    {
        return base64_encode(stream_get_contents($this->avatar));
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }
}
