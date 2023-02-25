<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * SAprovisionements
 *
 * @ORM\Table(name="s_aprovisionements", uniqueConstraints={@ORM\UniqueConstraint(name="code_ap", columns={"code_ap"})}, indexes={@ORM\Index(name="fk_7", columns={"id_magasin"}), @ORM\Index(name="FK_ap_product", columns={"product_id"}), @ORM\Index(name="FK_ap_user", columns={"user_id"})})
 * @ORM\Entity
 */
class SAprovisionements
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
     * @ORM\Column(name="amount", type="integer", nullable=true)
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateposted", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateposted = 'CURRENT_TIMESTAMP';

    /**
     * @var string|null
     *
     * @ORM\Column(name="code_ap", type="string", length=100, nullable=true)
     */
    private $codeAp;

    /**
     * @var int|null
     *
     * @ORM\Column(name="fournisseur_user", type="integer", nullable=true)
     */
    private $fournisseurUser;

    /**
     * @var float|null
     *
     * @ORM\Column(name="buying_price", type="float", precision=10, scale=0, nullable=true)
     */
    private $buyingPrice;

    /**
     * @var float|null
     *
     * @ORM\Column(name="sell_price", type="float", precision=10, scale=0, nullable=true)
     */
    private $sellPrice;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code_ap_name", type="string", length=255, nullable=true)
     */
    private $codeApName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="id_facture", type="string", length=255, nullable=true)
     */
    private $idFacture;

    /**
     * @var string|null
     *
     * @ORM\Column(name="id_ap", type="string", length=255, nullable=true)
     */
    private $idAp;

    /**
     * @var int
     *
     * @ORM\Column(name="amount_restant", type="integer", nullable=false)
     */
    private $amountRestant = '0';

    /**
     * @var float|null
     *
     * @ORM\Column(name="price_vente_ttc", type="float", precision=10, scale=0, nullable=true)
     */
    private $priceVenteTtc;

    /**
     * @var float|null
     *
     * @ORM\Column(name="price_achat_ttc", type="float", precision=10, scale=0, nullable=true)
     */
    private $priceAchatTtc;

    /**
     * @var float|null
     *
     * @ORM\Column(name="price_tva", type="float", precision=10, scale=0, nullable=true)
     */
    private $priceTva;

    /**
     * @var float|null
     *
     * @ORM\Column(name="tva", type="float", precision=10, scale=0, nullable=true)
     */
    private $tva;

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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): self
    {
        $this->amount = $amount;

        return $this;
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

    public function getCodeAp(): ?string
    {
        return $this->codeAp;
    }

    public function setCodeAp(?string $codeAp): self
    {
        $this->codeAp = $codeAp;

        return $this;
    }

    public function getFournisseurUser(): ?int
    {
        return $this->fournisseurUser;
    }

    public function setFournisseurUser(?int $fournisseurUser): self
    {
        $this->fournisseurUser = $fournisseurUser;

        return $this;
    }

    public function getBuyingPrice(): ?float
    {
        return $this->buyingPrice;
    }

    public function setBuyingPrice(?float $buyingPrice): self
    {
        $this->buyingPrice = $buyingPrice;

        return $this;
    }

    public function getSellPrice(): ?float
    {
        return $this->sellPrice;
    }

    public function setSellPrice(?float $sellPrice): self
    {
        $this->sellPrice = $sellPrice;

        return $this;
    }

    public function getCodeApName(): ?string
    {
        return $this->codeApName;
    }

    public function setCodeApName(?string $codeApName): self
    {
        $this->codeApName = $codeApName;

        return $this;
    }

    public function getIdFacture(): ?string
    {
        return $this->idFacture;
    }

    public function setIdFacture(?string $idFacture): self
    {
        $this->idFacture = $idFacture;

        return $this;
    }

    public function getIdAp(): ?string
    {
        return $this->idAp;
    }

    public function setIdAp(?string $idAp): self
    {
        $this->idAp = $idAp;

        return $this;
    }

    public function getAmountRestant(): ?int
    {
        return $this->amountRestant;
    }

    public function setAmountRestant(int $amountRestant): self
    {
        $this->amountRestant = $amountRestant;

        return $this;
    }

    public function getPriceVenteTtc(): ?float
    {
        return $this->priceVenteTtc;
    }

    public function setPriceVenteTtc(?float $priceVenteTtc): self
    {
        $this->priceVenteTtc = $priceVenteTtc;

        return $this;
    }

    public function getPriceAchatTtc(): ?float
    {
        return $this->priceAchatTtc;
    }

    public function setPriceAchatTtc(?float $priceAchatTtc): self
    {
        $this->priceAchatTtc = $priceAchatTtc;

        return $this;
    }

    public function getPriceTva(): ?float
    {
        return $this->priceTva;
    }

    public function setPriceTva(?float $priceTva): self
    {
        $this->priceTva = $priceTva;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(?float $tva): self
    {
        $this->tva = $tva;

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
