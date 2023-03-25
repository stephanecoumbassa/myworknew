<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ApiResource()
 *
 * SProduct
 *
 * @ORM\Table(name="s_product", indexes={@ORM\Index(name="fk_1", columns={"id_magasin"}), @ORM\Index(name="fk_agent_product", columns={"agent_user"}), @ORM\Index(name="FK_product_categorie", columns={"product_categories_id"})})
 * @ORM\Entity
 */
class SProduct
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="alert_threshold", type="integer", nullable=true)
     */
    private $alertThreshold;

    /**
     * @var string|null
     *
     * @ORM\Column(name="product_desc", type="text", length=0, nullable=true)
     */
    private $productDesc;

    /**
     * @var string|null
     *
     * @ORM\Column(name="product_url_img", type="string", length=255, nullable=true)
     */
    private $productUrlImg;

    /**
     * @var int|null
     *
     * @ORM\Column(name="amount", type="integer", nullable=true)
     */
    private $amount = '0';

    /**
     * @var float|null
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=true)
     */
    private $price;

    /**
     * @var float|null
     *
     * @ORM\Column(name="sales_price", type="float", precision=10, scale=0, nullable=true)
     */
    private $salesPrice;

    /**
     * @var float|null
     *
     * @ORM\Column(name="price_ttc", type="float", precision=10, scale=0, nullable=true)
     */
    private $priceTtc;

    /**
     * @var float|null
     *
     * @ORM\Column(name="price_sales_ttc", type="float", precision=10, scale=0, nullable=true)
     */
    private $priceSalesTtc;

    /**
     * @var float
     *
     * @ORM\Column(name="tva", type="float", precision=10, scale=0, nullable=false)
     */
    private $tva;

    /**
     * @var float|null
     *
     * @ORM\Column(name="price_tva", type="float", precision=10, scale=0, nullable=true)
     */
    private $priceTva;

    /**
     * @var float|null
     *
     * @ORM\Column(name="priceweb", type="float", precision=10, scale=0, nullable=true)
     */
    private $priceweb;

    /**
     * @var string|null
     *
     * @ORM\Column(name="color", type="string", length=255, nullable=true)
     */
    private $color;

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
     * @var \SUsers
     *
     * @ORM\ManyToOne(targetEntity="SUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agent_user", referencedColumnName="id")
     * })
     */
    private $agentUser;

    /**
     * @var \SProductCategories
     *
     * @ORM\ManyToOne(targetEntity="SProductCategories")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_categories_id", referencedColumnName="id")
     * })
     */
    private $productCategories;

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

    public function getAlertThreshold(): ?int
    {
        return $this->alertThreshold;
    }

    public function setAlertThreshold(?int $alertThreshold): self
    {
        $this->alertThreshold = $alertThreshold;

        return $this;
    }

    public function getProductDesc(): ?string
    {
        return $this->productDesc;
    }

    public function setProductDesc(?string $productDesc): self
    {
        $this->productDesc = $productDesc;

        return $this;
    }

    public function getProductUrlImg(): ?string
    {
        return $this->productUrlImg;
    }

    public function setProductUrlImg(?string $productUrlImg): self
    {
        $this->productUrlImg = $productUrlImg;

        return $this;
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSalesPrice(): ?float
    {
        return $this->salesPrice;
    }

    public function setSalesPrice(?float $salesPrice): self
    {
        $this->salesPrice = $salesPrice;

        return $this;
    }

    public function getPriceTtc(): ?float
    {
        return $this->priceTtc;
    }

    public function setPriceTtc(?float $priceTtc): self
    {
        $this->priceTtc = $priceTtc;

        return $this;
    }

    public function getPriceSalesTtc(): ?float
    {
        return $this->priceSalesTtc;
    }

    public function setPriceSalesTtc(?float $priceSalesTtc): self
    {
        $this->priceSalesTtc = $priceSalesTtc;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): self
    {
        $this->tva = $tva;

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

    public function getPriceweb(): ?float
    {
        return $this->priceweb;
    }

    public function setPriceweb(?float $priceweb): self
    {
        $this->priceweb = $priceweb;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

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

    public function getAgentUser(): ?SUsers
    {
        return $this->agentUser;
    }

    public function setAgentUser(?SUsers $agentUser): self
    {
        $this->agentUser = $agentUser;

        return $this;
    }

    public function getProductCategories(): ?SProductCategories
    {
        return $this->productCategories;
    }

    public function setProductCategories(?SProductCategories $productCategories): self
    {
        $this->productCategories = $productCategories;

        return $this;
    }


}
