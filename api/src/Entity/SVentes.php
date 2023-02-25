<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * SVentes
 *
 * @ORM\Table(name="s_ventes", indexes={@ORM\Index(name="fk_4", columns={"id_magasin"}), @ORM\Index(name="FK_vente_product", columns={"product_id"}), @ORM\Index(name="FK_vente_user", columns={"user_id"})})
 * @ORM\Entity
 */
class SVentes
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
     * @ORM\Column(name="quantite_vendu", type="integer", nullable=true)
     */
    private $quantiteVendu;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_unitaire", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixUnitaire;

    /**
     * @var float
     *
     * @ORM\Column(name="montant_vendu", type="float", precision=10, scale=0, nullable=false)
     */
    private $montantVendu;

    /**
     * @var float
     *
     * @ORM\Column(name="remise_unite", type="float", precision=10, scale=0, nullable=false)
     */
    private $remiseUnite;

    /**
     * @var float|null
     *
     * @ORM\Column(name="remise_totale", type="float", precision=10, scale=0, nullable=true)
     */
    private $remiseTotale;

    /**
     * @var float|null
     *
     * @ORM\Column(name="benefice_vente", type="float", precision=10, scale=0, nullable=true)
     */
    private $beneficeVente;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_posted", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $datePosted = 'CURRENT_TIMESTAMP';

    /**
     * @var string|null
     *
     * @ORM\Column(name="id_vente", type="string", length=15, nullable=true)
     */
    private $idVente;

    /**
     * @var int|null
     *
     * @ORM\Column(name="fournisseur_id", type="integer", nullable=true)
     */
    private $fournisseurId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="client_id", type="integer", nullable=true)
     */
    private $clientId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code_ap", type="string", length=255, nullable=true)
     */
    private $codeAp;

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

    public function getQuantiteVendu(): ?int
    {
        return $this->quantiteVendu;
    }

    public function setQuantiteVendu(?int $quantiteVendu): self
    {
        $this->quantiteVendu = $quantiteVendu;

        return $this;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(float $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    public function getMontantVendu(): ?float
    {
        return $this->montantVendu;
    }

    public function setMontantVendu(float $montantVendu): self
    {
        $this->montantVendu = $montantVendu;

        return $this;
    }

    public function getRemiseUnite(): ?float
    {
        return $this->remiseUnite;
    }

    public function setRemiseUnite(float $remiseUnite): self
    {
        $this->remiseUnite = $remiseUnite;

        return $this;
    }

    public function getRemiseTotale(): ?float
    {
        return $this->remiseTotale;
    }

    public function setRemiseTotale(?float $remiseTotale): self
    {
        $this->remiseTotale = $remiseTotale;

        return $this;
    }

    public function getBeneficeVente(): ?float
    {
        return $this->beneficeVente;
    }

    public function setBeneficeVente(?float $beneficeVente): self
    {
        $this->beneficeVente = $beneficeVente;

        return $this;
    }

    public function getDatePosted(): ?\DateTimeInterface
    {
        return $this->datePosted;
    }

    public function setDatePosted(\DateTimeInterface $datePosted): self
    {
        $this->datePosted = $datePosted;

        return $this;
    }

    public function getIdVente(): ?string
    {
        return $this->idVente;
    }

    public function setIdVente(?string $idVente): self
    {
        $this->idVente = $idVente;

        return $this;
    }

    public function getFournisseurId(): ?int
    {
        return $this->fournisseurId;
    }

    public function setFournisseurId(?int $fournisseurId): self
    {
        $this->fournisseurId = $fournisseurId;

        return $this;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(?int $clientId): self
    {
        $this->clientId = $clientId;

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
