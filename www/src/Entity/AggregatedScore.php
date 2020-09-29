<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AggregatedScoreRepository;

/**
 * @ORM\Entity(repositoryClass=AggregatedScoreRepository::class)
 * @ORM\Table(indexes={@ORM\Index(name="hotelid_date", columns={"hotel_id", "created_date"})})
 */
class AggregatedScore
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Hotel::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $hotel;

    /**
     * @ORM\Column(name="created_date", type="date")
     */
    private $createdDate;

    /**
     * @ORM\Column(type="float")
     */
    private $score;

    /**
     * @ORM\Column(type="integer")
     */
    private $reviews;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): self
    {
        $this->hotel = $hotel;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getReviews(): ?int
    {
        return $this->reviews;
    }

    public function setReviews(int $reviews): self
    {
        $this->reviews = $reviews;

        return $this;
    }
}
