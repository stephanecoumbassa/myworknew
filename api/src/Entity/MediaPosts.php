<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ApiResource()
 * MediaPosts
 *
 * @ORM\Table(name="media_posts", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id", "id_ligne", "name", "typerubrique_id", "media_types_id"})}, indexes={@ORM\Index(name="fk_media_posts_media_types1", columns={"media_types_id"}), @ORM\Index(name="fk_photos_posts_typerubrique1_idx", columns={"typerubrique_id"}), @ORM\Index(name="id_ligne", columns={"id_ligne"})})
 * @ORM\Entity
 */
class MediaPosts
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
     * @var int|null
     *
     * @ORM\Column(name="id_ligne", type="integer", nullable=true)
     */
    private $idLigne;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="typerubrique_id", type="integer", nullable=false)
     */
    private $typerubriqueId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="media_types_id", type="integer", nullable=true)
     */
    private $mediaTypesId;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIdLigne(): ?int
    {
        return $this->idLigne;
    }

    public function setIdLigne(?int $idLigne): self
    {
        $this->idLigne = $idLigne;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getMediaTypesId(): ?int
    {
        return $this->mediaTypesId;
    }

    public function setMediaTypesId(?int $mediaTypesId): self
    {
        $this->mediaTypesId = $mediaTypesId;

        return $this;
    }


}
