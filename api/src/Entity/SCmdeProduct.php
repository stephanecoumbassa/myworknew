<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * SCmdeProduct
 *
 * @ORM\Table(name="s_cmde_product", indexes={@ORM\Index(name="fk_2", columns={"id_magasin"}), @ORM\Index(name="fk_cmde_product", columns={"product_id"}), @ORM\Index(name="FK_cmde_user", columns={"user_id"})})
 * @ORM\Entity
 */
class SCmdeProduct
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
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_posted", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $datePosted = 'CURRENT_TIMESTAMP';

    /**
     * @var bool
     *
     * @ORM\Column(name="statut", type="boolean", nullable=false)
     */
    private $statut = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="id_cmde", type="string", length=100, nullable=true)
     */
    private $idCmde;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="client_name", type="string", length=255, nullable=true)
     */
    private $clientName;

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
     * @var \SProduct
     *
     * @ORM\ManyToOne(targetEntity="SProduct")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    /**
     * @var \SUsers
     *
     * @ORM\ManyToOne(targetEntity="SUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getDatePosted(): ?\DateTimeInterface
    {
        return $this->datePosted;
    }

    public function setDatePosted(?\DateTimeInterface $datePosted): self
    {
        $this->datePosted = $datePosted;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getIdCmde(): ?string
    {
        return $this->idCmde;
    }

    public function setIdCmde(?string $idCmde): self
    {
        $this->idCmde = $idCmde;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(?string $clientName): self
    {
        $this->clientName = $clientName;

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

    public function getProduct(): ?SProduct
    {
        return $this->product;
    }

    public function setProduct(?SProduct $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getUser(): ?SUsers
    {
        return $this->user;
    }

    public function setUser(?SUsers $user): self
    {
        $this->user = $user;

        return $this;
    }


}
