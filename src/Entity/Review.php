<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\ReviewCreateController;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 *      normalizationContext={"groups"={"read:review"},{"read:book"}},
 *      attributes={
 *          "order"={"rating":"DESC"}     
 *      },
 *      collectionOperations={
 *          "get",
 *          "post"={
 *              "security"="is_granted('IS_AUTHENTICATED_FULLY')",
 *              "controller"=App\Controller\Api\ReviewCreateController::class,
 *              "denormalization_context"={"groups"={"create:review"}}
 *          },
 *      },
 *      itemOperations={
 *          "get",
 *          "put"={ "security" = "is_granted('EDIT_REVIEW', object)","denormalization_context"={"groups"={"update:review"}} },
 *          "delete"={ "security" = "is_granted('DELETE_REVIEW', object)" },
 *      }
 * )
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 * @ApiFilter(SearchFilter::class,
 *      properties={"book": "exact"})
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:review","read:book"})
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"read:review","create:review","update:review","read:book"})
     */
    private $rating;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read:review","create:review","update:review","read:book"})
     */
    private $body;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reviews")
     * @Groups({"read:review","create:review","read:book"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="reviews")
     * @Groups({"read:review","create:review"})
     */
    private $book;

    public function __toString()
    {
        return $this->book.' - '.$this->author;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

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

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }
}
