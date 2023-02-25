<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Test
 *
 * @ORM\Table(name="test")
 * @ORM\Entity
 */
class Test
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
     * @var float|null
     *
     * @ORM\Column(name="pa", type="float", precision=10, scale=0, nullable=true)
     */
    private $pa;

    /**
     * @var int|null
     *
     * @ORM\Column(name="qte", type="integer", nullable=true)
     */
    private $qte;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_product", type="integer", nullable=true)
     */
    private $idProduct;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPa(): ?float
    {
        return $this->pa;
    }

    public function setPa(?float $pa): self
    {
        $this->pa = $pa;

        return $this;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(?int $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getIdProduct(): ?int
    {
        return $this->idProduct;
    }

    public function setIdProduct(?int $idProduct): self
    {
        $this->idProduct = $idProduct;

        return $this;
    }


}
