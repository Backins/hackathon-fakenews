<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="ReviewRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userReview")
     * @ORM\JoinColumn(referencedColumnName="id",nullable=false)
     */
    private $userReview;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $urlArticle;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserReview(): ?User
    {
        return $this->userReview;
    }

    public function setUserReview(?User $userReview): self
    {
        $this->userReview = $userReview;

        return $this;
    }

    public function getUrlArticle(): ?string
    {
        return $this->urlArticle;
    }

    public function setUrlArticle(string $urlArticle): self
    {
        $this->urlArticle = $urlArticle;

        return $this;
    }


}
