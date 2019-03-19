<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TargetSource")
 * @ORM\Table(name="target_source")
 */
class TargetSource
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $domain;

    /**
     * @ORM\Column(type="integer")
     */
    private $confidenceLevel;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }


    public function getConfidenceLevel(): ?int
    {
        return $this->confidenceLevel;
    }



    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function setConfidenceLevel(int $confidenceLevel): self
    {
        $this->confidenceLevel = $confidenceLevel;

        return $this;
    }

}
