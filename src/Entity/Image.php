<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @Vich\Uploadable()
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Assert\Type("integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Assert\Length(
     * min = 2,
     * max = 50,
     * minMessage = "Le nom doit faire au minimum {{ limit }} caractères.",
     * maxMessage = "Le nom ne doit pas dépasser {{ limit }} caractères."
     * )
     * @Assert\Type("string")
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type("string")
     * @var null|string
     */
    private ?string $url = null;

    /**
     * @Vich\UploadableField(mapping="url_file", fileNameProperty="url")
     * @Assert\File(
     *     mimeTypes = {"image/*"},
     *     mimeTypesMessage="Veuillez ne choisir que des fichiers de type image."
     *     )
     * @var null|File
     */
    private ?File $urlFile = null;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\Type("string")
     * @Assert\NotBlank
     */
    private string $category;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Type("string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min = 3,
     *     max = 255,
     *     minMessage = "La description doit faire au minimum {{ limit }} caractères.",
     *     maxMessage = "La description ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private string $alternativeText;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     * @Assert\NotBlank
     */
    private datetime $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="images")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Article $article;

    /**
     * @return array
     */
    #[ArrayShape(['id' => "", 'name' => "string", 'alternativeText' => "string", 'url' => "string"])]
    public function getArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'alternativeText' => $this->alternativeText,
            'url' => '/uploads/' . $this->url
        ];
    }

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAlternativeText(): ?string
    {
        return $this->alternativeText;
    }

    public function setAlternativeText(string $alternativeText): self
    {
        $this->alternativeText = $alternativeText;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getArticle(): Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): ?self
    {
        $this->article = $article;

        return $this;
    }

    /**
     * @return ?File
     */
    public function getUrlFile(): ?File
    {
        return $this->urlFile;
    }

    /**
     * @param ?File $urlFile
     * @return self
     */
    public function setUrlFile(?File $urlFile): self
    {
        $this->urlFile = $urlFile;
        if (null !== $urlFile) {
            $this->updatedAt = new DateTime('now');
        }
        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
