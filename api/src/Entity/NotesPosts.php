<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * NotesPosts
 *
 * @ORM\Table(name="notes_posts", uniqueConstraints={@ORM\UniqueConstraint(name="typerubrique_id", columns={"typerubrique_id", "id_ligne", "babinaute_id"})}, indexes={@ORM\Index(name="fk_notes_posts_typerubrique1_idx", columns={"typerubrique_id"})})
 * @ORM\Entity
 */
class NotesPosts
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
     * @ORM\Column(name="note", type="integer", nullable=true)
     */
    private $note;

    /**
     * @var int|null
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

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

    /**
     * @var int
     *
     * @ORM\Column(name="babinaute_id", type="integer", nullable=false)
     */
    private $babinauteId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ip", type="string", length=20, nullable=true)
     */
    private $ip;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $date = 'CURRENT_TIMESTAMP';

    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = '0';

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

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

    public function getBabinauteId(): ?int
    {
        return $this->babinauteId;
    }

    public function setBabinauteId(int $babinauteId): self
    {
        $this->babinauteId = $babinauteId;

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

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }


}
