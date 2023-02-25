<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * SLoyers
 *
 * @ORM\Table(name="s_loyers")
 * @ORM\Entity
 */
class SLoyers
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
     * @ORM\Column(name="locataireid", type="integer", nullable=false)
     */
    private $locataireid;

    /**
     * @var int
     *
     * @ORM\Column(name="appartementid", type="integer", nullable=false)
     */
    private $appartementid;

    /**
     * @var int
     *
     * @ORM\Column(name="montant", type="integer", nullable=false)
     */
    private $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="reste", type="integer", nullable=false)
     */
    private $reste;

    /**
     * @var int|null
     *
     * @ORM\Column(name="mois", type="integer", nullable=true)
     */
    private $mois;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datedebut", type="datetime", nullable=true)
     */
    private $datedebut;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="datefin", type="datetime", nullable=true)
     */
    private $datefin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocataireid(): ?int
    {
        return $this->locataireid;
    }

    public function setLocataireid(int $locataireid): self
    {
        $this->locataireid = $locataireid;

        return $this;
    }

    public function getAppartementid(): ?int
    {
        return $this->appartementid;
    }

    public function setAppartementid(int $appartementid): self
    {
        $this->appartementid = $appartementid;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReste(): ?int
    {
        return $this->reste;
    }

    public function setReste(int $reste): self
    {
        $this->reste = $reste;

        return $this;
    }

    public function getMois(): ?int
    {
        return $this->mois;
    }

    public function setMois(?int $mois): self
    {
        $this->mois = $mois;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(?\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }


}
