<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * Typerubrique
 *
 * @ORM\Table(name="typerubrique")
 * @ORM\Entity
 */
class Typerubrique
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
     * @var string|null
     *
     * @ORM\Column(name="slug", type="string", length=100, nullable=true)
     */
    private $slug;

    /**
     * @var string|null
     *
     * @ORM\Column(name="table_name", type="string", length=255, nullable=true)
     */
    private $tableName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="folder_upload", type="string", length=255, nullable=true)
     */
    private $folderUpload;

    /**
     * @var string|null
     *
     * @ORM\Column(name="folder_json", type="string", length=255, nullable=true)
     */
    private $folderJson;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function setTableName(?string $tableName): self
    {
        $this->tableName = $tableName;

        return $this;
    }

    public function getFolderUpload(): ?string
    {
        return $this->folderUpload;
    }

    public function setFolderUpload(?string $folderUpload): self
    {
        $this->folderUpload = $folderUpload;

        return $this;
    }

    public function getFolderJson(): ?string
    {
        return $this->folderJson;
    }

    public function setFolderJson(?string $folderJson): self
    {
        $this->folderJson = $folderJson;

        return $this;
    }


}
