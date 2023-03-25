<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * SCorelationCmdeVentes
 *
 * @ORM\Table(name="s_corelation_cmde_ventes", indexes={@ORM\Index(name="fk_3", columns={"id_magasin"}), @ORM\Index(name="FK_corelation_cmde", columns={"cmde_id"}), @ORM\Index(name="FK_corelation_vente", columns={"vente_id"})})
 * @ORM\Entity
 */
class SCorelationCmdeVentes
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateposted", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateposted = 'CURRENT_TIMESTAMP';

    /**
     * @var \Magasin
     *
     * @ORM\ManyToOne(targetEntity="Magasin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_magasin", referencedColumnName="id")
     * })
     */
    private $idMagasin;

    /**
     * @var \SCmdeProduct
     *
     * @ORM\ManyToOne(targetEntity="SCmdeProduct")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cmde_id", referencedColumnName="id")
     * })
     */
    private $cmde;

    /**
     * @var \SVentes
     *
     * @ORM\ManyToOne(targetEntity="SVentes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vente_id", referencedColumnName="id")
     * })
     */
    private $vente;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateposted(): ?\DateTimeInterface
    {
        return $this->dateposted;
    }

    public function setDateposted(\DateTimeInterface $dateposted): self
    {
        $this->dateposted = $dateposted;

        return $this;
    }

    public function getIdMagasin(): ?Magasin
    {
        return $this->idMagasin;
    }

    public function setIdMagasin(?Magasin $idMagasin): self
    {
        $this->idMagasin = $idMagasin;

        return $this;
    }

    public function getCmde(): ?SCmdeProduct
    {
        return $this->cmde;
    }

    public function setCmde(?SCmdeProduct $cmde): self
    {
        $this->cmde = $cmde;

        return $this;
    }

    public function getVente(): ?SVentes
    {
        return $this->vente;
    }

    public function setVente(?SVentes $vente): self
    {
        $this->vente = $vente;

        return $this;
    }


}
