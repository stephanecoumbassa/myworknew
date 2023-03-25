<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * SpecialityAutocomplete
 *
 * @ORM\Table(name="speciality_autocomplete", indexes={@ORM\Index(name="speciality_autocomplete_speciality_id_fk", columns={"speciality_id"})})
 * @ORM\Entity
 */
class SpecialityAutocomplete
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
     * @var int
     *
     * @ORM\Column(name="speciality_id", type="integer", nullable=false)
     */
    private $specialityId;

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

    public function getSpecialityId(): ?int
    {
        return $this->specialityId;
    }

    public function setSpecialityId(int $specialityId): self
    {
        $this->specialityId = $specialityId;

        return $this;
    }


}
