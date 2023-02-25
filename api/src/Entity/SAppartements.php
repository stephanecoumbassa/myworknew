<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * SAppartements
 *
 * @ORM\Table(name="s_appartements")
 * @ORM\Entity
 */
class SAppartements
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

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_batiment", type="integer", nullable=true)
     */
    private $idBatiment;

    /**
     * @var int|null
     *
     * @ORM\Column(name="prix_mois", type="integer", nullable=true)
     */
    private $prixMois;

    /**
     * @var int|null
     *
     * @ORM\Column(name="prix_jour", type="integer", nullable=true)
     */
    private $prixJour;

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

    public function getIdBatiment(): ?int
    {
        return $this->idBatiment;
    }

    public function setIdBatiment(?int $idBatiment): self
    {
        $this->idBatiment = $idBatiment;

        return $this;
    }

    public function getPrixMois(): ?int
    {
        return $this->prixMois;
    }

    public function setPrixMois(?int $prixMois): self
    {
        $this->prixMois = $prixMois;

        return $this;
    }

    public function getPrixJour(): ?int
    {
        return $this->prixJour;
    }

    public function setPrixJour(?int $prixJour): self
    {
        $this->prixJour = $prixJour;

        return $this;
    }


}
