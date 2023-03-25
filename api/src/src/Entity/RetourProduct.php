<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * RetourProduct
 *
 * @ORM\Table(name="retour_product", indexes={@ORM\Index(name="fk_agent_sorti_id", columns={"agent_sorti_id"}), @ORM\Index(name="fk_agent_vente_id", columns={"agent_vente_id"}), @ORM\Index(name="fk_fournisseur_id", columns={"fournisseur_id"}), @ORM\Index(name="fk_product_retour", columns={"product_id"})})
 * @ORM\Entity
 */
class RetourProduct
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_retour", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateRetour = 'CURRENT_TIMESTAMP';

    /**
     * @var string|null
     *
     * @ORM\Column(name="motif", type="string", length=200, nullable=true)
     */
    private $motif;

    /**
     * @var int|null
     *
     * @ORM\Column(name="qte_vendu", type="integer", nullable=true)
     */
    private $qteVendu;

    /**
     * @var string
     *
     * @ORM\Column(name="code_ap", type="string", length=255, nullable=false)
     */
    private $codeAp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_vente", type="datetime", nullable=false)
     */
    private $dateVente;

    /**
     * @var \SUsers
     *
     * @ORM\ManyToOne(targetEntity="SUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agent_sorti_id", referencedColumnName="id")
     * })
     */
    private $agentSorti;

    /**
     * @var \SUsers
     *
     * @ORM\ManyToOne(targetEntity="SUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agent_vente_id", referencedColumnName="id")
     * })
     */
    private $agentVente;

    /**
     * @var \SUsers
     *
     * @ORM\ManyToOne(targetEntity="SUsers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fournisseur_id", referencedColumnName="id")
     * })
     */
    private $fournisseur;

    /**
     * @var \SProduct
     *
     * @ORM\ManyToOne(targetEntity="SProduct")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->dateRetour;
    }

    public function setDateRetour(?\DateTimeInterface $dateRetour): self
    {
        $this->dateRetour = $dateRetour;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getQteVendu(): ?int
    {
        return $this->qteVendu;
    }

    public function setQteVendu(?int $qteVendu): self
    {
        $this->qteVendu = $qteVendu;

        return $this;
    }

    public function getCodeAp(): ?string
    {
        return $this->codeAp;
    }

    public function setCodeAp(string $codeAp): self
    {
        $this->codeAp = $codeAp;

        return $this;
    }

    public function getDateVente(): ?\DateTimeInterface
    {
        return $this->dateVente;
    }

    public function setDateVente(\DateTimeInterface $dateVente): self
    {
        $this->dateVente = $dateVente;

        return $this;
    }

    public function getAgentSorti(): ?SUsers
    {
        return $this->agentSorti;
    }

    public function setAgentSorti(?SUsers $agentSorti): self
    {
        $this->agentSorti = $agentSorti;

        return $this;
    }

    public function getAgentVente(): ?SUsers
    {
        return $this->agentVente;
    }

    public function setAgentVente(?SUsers $agentVente): self
    {
        $this->agentVente = $agentVente;

        return $this;
    }

    public function getFournisseur(): ?SUsers
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?SUsers $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

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


}
