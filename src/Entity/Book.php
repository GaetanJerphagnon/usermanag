<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"read:book"}},
 *      paginationItemsPerPage=5,
 *      attributes={
 *          "order"={"publicationDate":"DESC"}     
 *      },
 *      collectionOperations={
 *          "get",
 *          "post"={
 *              "security"="is_granted('IS_AUTHENTICATED_FULLY')",
 *              "denormalization_context"={"groups"={"create:book"}}
 *          },
 *      },
 *      itemOperations={
 *          "get",
 *          "put"={ "security" = "is_granted('EDIT_BOOK', object)","denormalization_context"={"groups"={"update:book"}} },
 *          "delete"={ "security" = "is_granted('DELETE_BOOK', object)" },
 *      }
 * )
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:book","read:review"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read:book","create:book","update:book","read:review"})
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:book","create:book","update:book","read:review"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read:book","create:book","update:book","read:review"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="books")
     * @Groups({"read:book","create:book","read:review"})
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read:book","create:book","read:review"})
     */
    private $publicationDate;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="book",cascade={"persist", "remove"})
     * @Groups({"read:book"})
     */
    private $reviews;

    public function __toString()
    {
        return $this->title.' - '.$this->author;
    }

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setBook($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getBook() === $this) {
                $review->setBook(null);
            }
        }

        return $this;
    }
}
