<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * LinksPosts
 *
 * @ORM\Table(name="links_posts", uniqueConstraints={@ORM\UniqueConstraint(name="name", columns={"name", "typerubrique_id", "id_ligne"})}, indexes={@ORM\Index(name="fk_links_posts_links_type1", columns={"links_type_id"}), @ORM\Index(name="fk_social_posts_typerubrique1_idx", columns={"typerubrique_id"}), @ORM\Index(name="id_ligne", columns={"id_ligne"})})
 * @ORM\Entity
 */
class LinksPosts
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
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=1000, nullable=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="links_type_id", type="integer", nullable=false)
     */
    private $linksTypeId;

    /**
     * @var int
     *
     * @ORM\Column(name="typerubrique_id", type="integer", nullable=false)
     */
    private $typerubriqueId;

    /**
     * @var int
     *
     * @ORM\Column(name="id_ligne", type="integer", nullable=false)
     */
    private $idLigne;

    public function getId(): ?string
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

    public function getLinksTypeId(): ?int
    {
        return $this->linksTypeId;
    }

    public function setLinksTypeId(int $linksTypeId): self
    {
        $this->linksTypeId = $linksTypeId;

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

    public function getIdLigne(): ?int
    {
        return $this->idLigne;
    }

    public function setIdLigne(int $idLigne): self
    {
        $this->idLigne = $idLigne;

        return $this;
    }


}
