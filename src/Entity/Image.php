<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Article;
use DateTime;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @Vich\Uploadable
 */
class Image
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
     * minMessage = "Le nom doit faire au minimum {{ limit }} caractères.",
     * maxMessage = "Le nom ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private string $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @var null|string
     */
    private $url;

    /**
     * @Vich\UploadableField(mapping="url_file", fileNameProperty="url")
     * @var File
     */
    private $urlFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $categorie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     * min = 2,
     * max = 255,
     * minMessage = "La description doit faire au minimum {{ limit }} caractères.",
     * maxMessage = "La description ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private string $texteAlternatif;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="image")
     * @ORM\JoinColumn(nullable=false)
     */
    private collection $articles;

    /**
     * @ORM\Column(type="datetime")
     */
    private datetime $updatedAt;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * @return mixed[]
     */
    public function getArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'texteAltenatif' => $this->texteAlternatif,
            'url' => '/uploads/' . $this->url
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getTexteAlternatif(): string
    {
        return $this->texteAlternatif;
    }

    public function setTexteAlternatif(string $texteAlternatif): self
    {
        $this->texteAlternatif = $texteAlternatif;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addImage($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            $article->removeImage($this);
        }

        return $this;
    }

    public function setUrlFile(File $image): self
    {
        $this->urlFile = $image;
        $this->updatedAt = new DateTime('now');
        return $this;
    }

    public function getUrlFile(): ?File
    {
        return $this->urlFile;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
