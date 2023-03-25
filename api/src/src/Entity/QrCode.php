<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 *
 * QrCode
 *
 * @ORM\Table(name="qr_code")
 * @ORM\Entity
 */
class QrCode
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
     * @ORM\Column(name="file", type="blob", length=0, nullable=true)
     */
    private $file;

    /**
     * @var int|null
     *
     * @ORM\Column(name="typerubrique", type="integer", nullable=true)
     */
    private $typerubrique;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_ligne", type="integer", nullable=true)
     */
    private $idLigne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getTyperubrique(): ?int
    {
        return $this->typerubrique;
    }

    public function setTyperubrique(?int $typerubrique): self
    {
        $this->typerubrique = $typerubrique;

        return $this;
    }

    public function getIdLigne(): ?int
    {
        return $this->idLigne;
    }

    public function setIdLigne(?int $idLigne): self
    {
        $this->idLigne = $idLigne;

        return $this;
    }


}
