<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 *
 * RelationsHasSpeciality
 *
 * @ORM\Table(name="relations_has_speciality", uniqueConstraints={@ORM\UniqueConstraint(name="PJ_ID", columns={"PJ_ID", "speciality_id", "autocomplete_id"})}, indexes={@ORM\Index(name="fk_PJ_has_specialites_specialites1", columns={"speciality_id"}), @ORM\Index(name="PJ_ID_2", columns={"PJ_ID"}), @ORM\Index(name="speciality_autocomplete_id_fk", columns={"autocomplete_id"})})
 * @ORM\Entity
 */
class RelationsHasSpeciality
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
     * @var int
     *
     * @ORM\Column(name="PJ_ID", type="integer", nullable=false)
     */
    private $pjId;

    /**
     * @var int
     *
     * @ORM\Column(name="speciality_id", type="integer", nullable=false)
     */
    private $specialityId;

    /**
     * @var int
     *
     * @ORM\Column(name="autocomplete_id", type="integer", nullable=false)
     */
    private $autocompleteId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPjId(): ?int
    {
        return $this->pjId;
    }

    public function setPjId(int $pjId): self
    {
        $this->pjId = $pjId;

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

    public function getAutocompleteId(): ?int
    {
        return $this->autocompleteId;
    }

    public function setAutocompleteId(int $autocompleteId): self
    {
        $this->autocompleteId = $autocompleteId;

        return $this;
    }


}
