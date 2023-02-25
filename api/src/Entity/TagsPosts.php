<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * TagsPosts
 *
 * @ORM\Table(name="tags_posts", uniqueConstraints={@ORM\UniqueConstraint(name="id_ligne_2", columns={"id_ligne", "typerubrique_id"})}, indexes={@ORM\Index(name="fk_tags_posts_typerubrique1_idx", columns={"typerubrique_id"}), @ORM\Index(name="id_ligne", columns={"id_ligne"})})
 * @ORM\Entity
 */
class TagsPosts
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var json|null
     *
     * @ORM\Column(name="tags", type="json", nullable=true)
     */
    private $tags;

    /**
     * @var int
     *
     * @ORM\Column(name="id_ligne", type="integer", nullable=false)
     */
    private $idLigne;

    /**
     * @var int
     *
     * @ORM\Column(name="typerubrique_id", type="integer", nullable=false)
     */
    private $typerubriqueId;

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

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): self
    {
        $this->tags = $tags;

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
