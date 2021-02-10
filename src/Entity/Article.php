<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Image;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     * min = 2,
     * max = 255,
     * minMessage = "Le titre doit faire au minimum {{ limit }} caractères.",
     * maxMessage = "Le titre ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private string $titre;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private \DateTimeInterface $date;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $section;

    /**
     * @ORM\ManyToMany(targetEntity=Image::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private Collection $image;

    public function __construct()
    {
        $this->image = new ArrayCollection();
    }

    /**
     * @return mixed[]
     */
    public function getArray(): array
    {
        $images = [];
        foreach ($this->image as $img) {
            $images[] = $img->getArray();
        }
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'date' => $this->date,
            'images' => $images,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(string $section): self
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Image $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        $this->image->removeElement($image);

        return $this;
    }
}
