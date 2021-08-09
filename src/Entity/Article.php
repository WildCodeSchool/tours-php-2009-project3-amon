<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
     * @Assert\Type("integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min = 3,
     *     max = 50,
     *     minMessage = "Le titre doit faire au minimum {{ limit }} caractères.",
     *     maxMessage = "Le titre ne doit pas dépasser {{ limit }} caractères.",
     *     )
     * @Assert\Type("string")
     */
    private string $title;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     */
    private \DateTimeInterface $date;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min = 15,
     *     max = 10000,
     *     minMessage = "Le contenu est trop court.",
     *     maxMessage = "Le contenu ne doit pas dépasser {{ limit }} caractères.",
     *     )
     * @Assert\Type("string")
     */
    private string $content;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type("bool")
     */
    private bool $isNews;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="article", orphanRemoval=true, cascade={"persist"})
     * @Assert\NotBlank
     * @Assert\Count(
     *     min = 1,
     *     minMessage = "Un article doit contenir au moins une image",
     * )
     */
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * @return array
     */
    public function getArray(): array
    {
        $images = [];
        foreach ($this->images as $img) {
            $images[] = $img->getArray();
        }
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'date' => $this->date,
            'images' => $images,
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getIsNews(): ?bool
    {
        return $this->isNews;
    }

    public function setIsNews(bool $isNews): self
    {
        $this->isNews = $isNews;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setArticle($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getArticle() === $this) {
                $image->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }
}
