<?php

namespace App\Entity;

use App\Repository\ProjectPortfolioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectPortfolioRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity("slug")
 */
class ProjectPortfolio
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $slug;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var array<mixed>
     * @ORM\Column(type="array")
     */
    private $pictures = [];

    /**
     * @var array<mixed>
     * @ORM\Column(type="array")
     */
    private $categories = [];

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $is_online = false;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $html_content;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @var \DateTimeInterface|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    /**
     * @return array<mixed>|null
     */
    public function getPictures(): ?array
    {
        return $this->pictures;
    }

    /**
     * @param array<mixed> $pictures
     */
    public function setPictures(array $pictures): self
    {
        $this->pictures = $pictures;

        return $this;
    }

    /**
     * @return array<mixed>|null
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }

    /**
     * @param array<mixed> $categories
     */
    public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getIsOnline(): ?bool
    {
        return $this->is_online;
    }

    public function setIsOnline(bool $is_online): self
    {
        $this->is_online = $is_online;

        return $this;
    }

    public function getHtmlContent(): ?string
    {
        return $this->html_content;
    }

    public function setHtmlContent(?string $html_content): self
    {
        $this->html_content = $html_content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        if (null === $this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime('now'));
        } else {
            $this->setUpdatedAt(new \DateTime('now'));
        }
    }
}
