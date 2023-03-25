<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * AddressPosts
 *
 * @ORM\Table(name="address_posts", indexes={@ORM\Index(name="fk_address_posts_typerubrique1_idx", columns={"typerubrique_id"}), @ORM\Index(name="id_ligne", columns={"id_ligne"})})
 * @ORM\Entity
 */
class AddressPosts
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_ligne", type="bigint", nullable=true)
     */
    private $idLigne;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="country_id", type="string", length=11, nullable=true)
     */
    private $countryId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="city_id", type="string", length=11, nullable=true)
     */
    private $cityId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="quartier_id", type="string", length=11, nullable=true)
     */
    private $quartierId;

    /**
     * @var float|null
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var float|null
     *
     * @ORM\Column(name="latitutde", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitutde;

    /**
     * @var int
     *
     * @ORM\Column(name="typerubrique_id", type="integer", nullable=false)
     */
    private $typerubriqueId;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIdLigne(): ?string
    {
        return $this->idLigne;
    }

    public function setIdLigne(?string $idLigne): self
    {
        $this->idLigne = $idLigne;

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

    public function getCountryId(): ?string
    {
        return $this->countryId;
    }

    public function setCountryId(?string $countryId): self
    {
        $this->countryId = $countryId;

        return $this;
    }

    public function getCityId(): ?string
    {
        return $this->cityId;
    }

    public function setCityId(?string $cityId): self
    {
        $this->cityId = $cityId;

        return $this;
    }

    public function getQuartierId(): ?string
    {
        return $this->quartierId;
    }

    public function setQuartierId(?string $quartierId): self
    {
        $this->quartierId = $quartierId;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitutde(): ?float
    {
        return $this->latitutde;
    }

    public function setLatitutde(?float $latitutde): self
    {
        $this->latitutde = $latitutde;

        return $this;
    }

    public function getTyperubriqueId(): ?int
    {
        return $this->typerubriqueId;
    }

    public function setTyperubriqueId(int $typerubriqueId): self
    {
        $this->typerubriqueId = $typerubriqueId;

        return $this;
    }


}
