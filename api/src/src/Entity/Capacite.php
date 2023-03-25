<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * Capacite
 *
 * @ORM\Table(name="capacite", indexes={@ORM\Index(name="fk_capacite_capacite_type1_idx", columns={"capacite_type_id"}), @ORM\Index(name="fk_capactite_PJ1_idx", columns={"PJ_ID"}), @ORM\Index(name="PJ_ID", columns={"PJ_ID"})})
 * @ORM\Entity
 */
class Capacite
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
     * @var int|null
     *
     * @ORM\Column(name="valeur", type="integer", nullable=true)
     */
    private $valeur;

    /**
     * @var string|null
     *
     * @ORM\Column(name="unite", type="string", length=45, nullable=true)
     */
    private $unite;

    /**
     * @var int
     *
     * @ORM\Column(name="PJ_ID", type="integer", nullable=false)
     */
    private $pjId;

    /**
     * @var int
     *
     * @ORM\Column(name="capacite_type_id", type="integer", nullable=false)
     */
    private $capaciteTypeId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(?int $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(?string $unite): self
    {
        $this->unite = $unite;

        return $this;
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

    public function getCapaciteTypeId(): ?int
    {
        return $this->capaciteTypeId;
    }

    public function setCapaciteTypeId(int $capaciteTypeId): self
    {
        $this->capaciteTypeId = $capaciteTypeId;

        return $this;
    }


}
