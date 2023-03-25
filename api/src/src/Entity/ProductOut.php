<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 *
 * ProductOut
 *
 * @ORM\Table(name="product_out", indexes={@ORM\Index(name="fk_6", columns={"id_magasin"}), @ORM\Index(name="fk_ap_product_out", columns={"code_ap"}), @ORM\Index(name="fk_product_out", columns={"id_product"})})
 * @ORM\Entity
 */
class ProductOut
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
     * @ORM\Column(name="quantite_livre", type="integer", nullable=false)
     */
    private $quantiteLivre;

    /**
     * @var int|null
     *
     * @ORM\Column(name="quantite_out", type="integer", nullable=true)
     */
    private $quantiteOut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_livraison", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateLivraison = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_sortie", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateSortie = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="id_vente", type="integer", nullable=false)
     */
    private $idVente;

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
     * @var \SAprovisionements
     *
     * @ORM\ManyToOne(targetEntity="SAprovisionements")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="code_ap", referencedColumnName="code_ap")
     * })
     */
    private $codeAp;

    /**
     * @var \SProduct
     *
     * @ORM\ManyToOne(targetEntity="SProduct")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_product", referencedColumnName="id")
     * })
     */
    private $idProduct;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantiteLivre(): ?int
    {
        return $this->quantiteLivre;
    }

    public function setQuantiteLivre(int $quantiteLivre): self
    {
        $this->quantiteLivre = $quantiteLivre;

        return $this;
    }

    public function getQuantiteOut(): ?int
    {
        return $this->quantiteOut;
    }

    public function setQuantiteOut(?int $quantiteOut): self
    {
        $this->quantiteOut = $quantiteOut;

        return $this;
    }

    public function getDateLivraison(): ?\DateTimeInterface
    {
        return $this->dateLivraison;
    }

    public function setDateLivraison(\DateTimeInterface $dateLivraison): self
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeInterface $dateSortie): self
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    public function getIdVente(): ?int
    {
        return $this->idVente;
    }

    public function setIdVente(int $idVente): self
    {
        $this->idVente = $idVente;

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

    public function getCodeAp(): ?SAprovisionements
    {
        return $this->codeAp;
    }

    public function setCodeAp(?SAprovisionements $codeAp): self
    {
        $this->codeAp = $codeAp;

        return $this;
    }

    public function getIdProduct(): ?SProduct
    {
        return $this->idProduct;
    }

    public function setIdProduct(?SProduct $idProduct): self
    {
        $this->idProduct = $idProduct;

        return $this;
    }


}
