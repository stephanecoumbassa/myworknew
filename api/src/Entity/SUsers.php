<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ApiResource()
 * SUsers
 *
 * @ORM\Table(name="s_users", uniqueConstraints={@ORM\UniqueConstraint(name="uk_email", columns={"email"}), @ORM\UniqueConstraint(name="UK_phone_number", columns={"phone_number"})}, indexes={@ORM\Index(name="fk_8", columns={"id_magasin"}), @ORM\Index(name="FK_type_user", columns={"type_users_id"})})
 * @ORM\Entity
 */
class SUsers implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=100, nullable=false)
     */
    private $telephone;


    /**
     * @var int|null
     *
     * @ORM\Column(name="telephone_code", type="integer", nullable=true)
     */
    private $telephone_code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="roles", type="string", length=255, nullable=true)
     */
    private $roles;

    /**
     * @var string|null
     *
     * @ORM\Column(name="uuid", type="string", length=255, nullable=true)
     */
    private $uuid;

//    /**
//     * @var \Magasin
//     *
//     * @ORM\ManyToOne(targetEntity="Magasin")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="id_magasin", referencedColumnName="id")
//     * })
//     */
    /**
     * @var int|null
     *
     * @ORM\Column(name="id_magasin", type="integer", nullable=true)
     */
    private $idMagasin;

//    /**
//     * @var \STypeUsers
//     *
//     * @ORM\ManyToOne(targetEntity="STypeUsers")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="type_users_id", referencedColumnName="id")
//     * })
//     */
    /**
     * @var int|null
     *
     * @ORM\Column(name="type_users_id", type="integer", nullable=true)
     */
    private $typeUsers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getTelephoneCode(): ?int
    {
        return $this->telephone_code;
    }


    public function setTelephoneCode(int $telephone_code)
    {
        $this->telephone_code = $telephone_code;
        return $this;
    }


    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles()
    {
//        return $this->roles;
        return [$this->roles];
    }

    public function setRoles(?string $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getIdMagasin(): ?int
    {
        return $this->idMagasin;
    }

    public function getIdShopId(): ?int
    {
        return $this->idMagasin;
    }

    public function getShopId(): ?int
    {
        return $this->idMagasin;
    }

    public function setIdMagasin(?Magasin $idMagasin): self
    {
        $this->idMagasin = $idMagasin;

        return $this;
    }

    public function getTypeUsers(): ?int
    {
        return $this->typeUsers;
    }

    public function setTypeUsers(?STypeUsers $typeUsers): self
    {
        $this->typeUsers = $typeUsers;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @param string|null $uuid
     * @return SUsers
     */
    public function setUuid(?string $uuid): SUsers
    {
        $this->uuid = $uuid;
        return $this;
    }


    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
