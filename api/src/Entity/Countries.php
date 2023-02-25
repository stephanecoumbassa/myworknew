<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ApiResource()
 * Countries
 *
 * @ORM\Table(name="countries")
 * @ORM\Entity
 */
class Countries
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
     * @ORM\Column(name="code", type="string", length=2, nullable=false, options={"fixed"=true})
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="nicename", type="string", length=80, nullable=false)
     */
    private $nicename;

    /**
     * @var string|null
     *
     * @ORM\Column(name="iso3", type="string", length=3, nullable=true, options={"fixed"=true})
     */
    private $iso3;

    /**
     * @var int
     *
     * @ORM\Column(name="indicatif", type="integer", nullable=false)
     */
    private $indicatif;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
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

    public function getNicename(): ?string
    {
        return $this->nicename;
    }

    public function setNicename(string $nicename): self
    {
        $this->nicename = $nicename;

        return $this;
    }

    public function getIso3(): ?string
    {
        return $this->iso3;
    }

    public function setIso3(?string $iso3): self
    {
        $this->iso3 = $iso3;

        return $this;
    }

    public function getIndicatif(): ?int
    {
        return $this->indicatif;
    }

    public function setIndicatif(int $indicatif): self
    {
        $this->indicatif = $indicatif;

        return $this;
    }


}
