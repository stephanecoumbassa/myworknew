<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * CommentsPosts
 *
 * @ORM\Table(name="comments_posts", indexes={@ORM\Index(name="fk_comments_posts_comments_type1_idx", columns={"comments_type_id"}), @ORM\Index(name="fk_comments_posts_typerubrique1_idx", columns={"typerubrique_id"})})
 * @ORM\Entity
 */
class CommentsPosts
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
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $date = 'CURRENT_TIMESTAMP';

    /**
     * @var int|null
     *
     * @ORM\Column(name="note", type="integer", nullable=true)
     */
    private $note;

    /**
     * @var string|null
     *
     * @ORM\Column(name="posts_id", type="string", length=100, nullable=true)
     */
    private $postsId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="comments_type", type="integer", nullable=true)
     */
    private $commentsType;

    /**
     * @var int
     *
     * @ORM\Column(name="typerubrique_id", type="integer", nullable=false)
     */
    private $typerubriqueId;

    /**
     * @var int
     *
     * @ORM\Column(name="comments_type_id", type="integer", nullable=false)
     */
    private $commentsTypeId;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
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

    public function getPostsId(): ?string
    {
        return $this->postsId;
    }

    public function setPostsId(?string $postsId): self
    {
        $this->postsId = $postsId;

        return $this;
    }

    public function getCommentsType(): ?int
    {
        return $this->commentsType;
    }

    public function setCommentsType(?int $commentsType): self
    {
        $this->commentsType = $commentsType;

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

    public function getCommentsTypeId(): ?int
    {
        return $this->commentsTypeId;
    }

    public function setCommentsTypeId(int $commentsTypeId): self
    {
        $this->commentsTypeId = $commentsTypeId;

        return $this;
    }


}
