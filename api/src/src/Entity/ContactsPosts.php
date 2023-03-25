<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * ContactsPosts
 *
 * @ORM\Table(name="contacts_posts", uniqueConstraints={@ORM\UniqueConstraint(name="id_ligne_2", columns={"id_ligne", "email", "telephone", "typerubrique_id"})}, indexes={@ORM\Index(name="email", columns={"email"}), @ORM\Index(name="fk_contacts_posts_typerubrique1_idx", columns={"typerubrique_id"}), @ORM\Index(name="id_ligne", columns={"id_ligne"}), @ORM\Index(name="telephone", columns={"telephone"})})
 * @ORM\Entity
 */
class ContactsPosts
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_ligne", type="integer", nullable=false)
     */
    private $idLigne;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @var int|null
     *
     * @ORM\Column(name="indicatif", type="integer", nullable=true)
     */
    private $indicatif;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telephone", type="string", length=100, nullable=true)
     */
    private $telephone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fax", type="string", length=100, nullable=true)
     */
    private $fax;

    /**
     * @var string|null
     *
     * @ORM\Column(name="bp", type="string", length=255, nullable=true)
     */
    private $bp;

    /**
     * @var string|null
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @var int
     *
     * @ORM\Column(name="typerubrique_id", type="integer", nullable=false)
     */
    private $typerubriqueId;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIdLigne(): ?int
    {
        return $this->idLigne;
    }

    public function setIdLigne(int $idLigne): self
    {
        $this->idLigne = $idLigne;

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

    public function getIndicatif(): ?int
    {
        return $this->indicatif;
    }

    public function setIndicatif(?int $indicatif): self
    {
        $this->indicatif = $indicatif;

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

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getBp(): ?string
    {
        return $this->bp;
    }

    public function setBp(?string $bp): self
    {
        $this->bp = $bp;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getTyperubriqueId(): ?int
    {
        return $this->typerubriqueId;
    }

    public function setTyperubriqueId(int $typerubriqueId): self
    {
        $this->typerubriqueId = $typerubriqueId;

        return $this;
    }


}
