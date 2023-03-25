<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;


/**
 *
 * @ApiResource()
 * SServices
 *
 * @ORM\Table(name="s_services")
 * @ORM\Entity
 */
class SServices
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="price", type="integer", nullable=true)
     */
    private $price;

    /**
     * @var int|null
     *
     * @ORM\Column(name="tva", type="integer", nullable=true)
     */
    private $tva;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code_comptable", type="string", length=255, nullable=true)
     */
    private $codeComptable;

    /**
     * @var string|null
     *
     * @ORM\Column(name="client", type="string", length=255, nullable=true)
     */
    private $client;

    /**
     * @var int|null
     *
     * @ORM\Column(name="telephone", type="integer", nullable=true)
     */
    private $telephone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var int|null
     *
     * @ORM\Column(name="clientid", type="integer", nullable=true)
     */
    private $clientid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_magasin", type="integer", nullable=true)
     */
    private $idMagasin;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $date = 'CURRENT_TIMESTAMP';

    /**
     * @var int|null
     *
     * @ORM\Column(name="versement1", type="integer", nullable=true)
     */
    private $versement1;

    /**
     * @var int|null
     *
     * @ORM\Column(name="versement2", type="integer", nullable=true)
     */
    private $versement2;

    /**
     * @var int|null
     *
     * @ORM\Column(name="versement3", type="integer", nullable=true)
     */
    private $versement3;

    /**
     * @var int|null
     *
     * @ORM\Column(name="versement4", type="integer", nullable=true)
     */
    private $versement4;

    /**
     * @var int|null
     *
     * @ORM\Column(name="versement5", type="integer", nullable=true)
     */
    private $versement5;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTva(): ?int
    {
        return $this->tva;
    }

    public function setTva(?int $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getCodeComptable(): ?string
    {
        return $this->codeComptable;
    }

    public function setCodeComptable(?string $codeComptable): self
    {
        $this->codeComptable = $codeComptable;

        return $this;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(?string $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(?int $telephone): self
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getClientid(): ?int
    {
        return $this->clientid;
    }

    public function setClientid(?int $clientid): self
    {
        $this->clientid = $clientid;

        return $this;
    }

    public function getIdMagasin(): ?int
    {
        return $this->idMagasin;
    }

    public function setIdMagasin(?int $idMagasin): self
    {
        $this->idMagasin = $idMagasin;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getVersement1(): ?int
    {
        return $this->versement1;
    }

    public function setVersement1(?int $versement1): self
    {
        $this->versement1 = $versement1;

        return $this;
    }

    public function getVersement2(): ?int
    {
        return $this->versement2;
    }

    public function setVersement2(?int $versement2): self
    {
        $this->versement2 = $versement2;

        return $this;
    }

    public function getVersement3(): ?int
    {
        return $this->versement3;
    }

    public function setVersement3(?int $versement3): self
    {
        $this->versement3 = $versement3;

        return $this;
    }

    public function getVersement4(): ?int
    {
        return $this->versement4;
    }

    public function setVersement4(?int $versement4): self
    {
        $this->versement4 = $versement4;

        return $this;
    }

    public function getVersement5(): ?int
    {
        return $this->versement5;
    }

    public function setVersement5(?int $versement5): self
    {
        $this->versement5 = $versement5;

        return $this;
    }


}
