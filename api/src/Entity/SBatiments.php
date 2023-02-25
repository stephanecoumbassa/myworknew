<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * SBatiments
 *
 * @ORM\Table(name="s_batiments")
 * @ORM\Entity
 */
class SBatiments
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
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_magasin", type="integer", nullable=true)
     */
    private $idMagasin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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


}
