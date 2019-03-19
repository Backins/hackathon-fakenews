<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Review")
 * @ORM\Table(name="review")
 */
class Review
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $articleId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userReview")
     * @ORM\JoinColumn(referencedColumnName="id",nullable=false)
     */
    private $userReview;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticleId(): ?int
    {
        return $this->articleId;
    }

    public function getUserReview(): ?User
    {
        return $this->userReview;
    }

    public function setArticleId(int $articleId): self
    {
        $this->articleId = $articleId;

        return $this;
    }

    public function setUserReview(?User $userReview): self
    {
        $this->userReview = $userReview;

        return $this;
    }


}
