<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\HotelRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=HotelRepository::class)
 */
class Hotel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="hotel_id", cascade={"persist", "remove"})
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity=AggregatedScore::class, mappedBy="hotel_id", cascade={"persist", "remove"})
     */
    private $aggregatedScores;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
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
            $review->setHotel($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            if ($review->getHotel() === $this) {
                $review->setHotel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AggregatedScore[]
     */
    public function getAggregatedScores(): Collection
    {
        return $this->aggregatedScores;
    }

    public function addAggregatedScore(AggregatedScore $aggregatedScore): self
    {
        if (!$this->aggregatedScores->contains($aggregatedScore)) {
            $this->aggregatedScores[] = $aggregatedScore;
            $aggregatedScore->setHotel($this);
        }

        return $this;
    }

    public function removeAggregatedScore(AggregatedScore $aggregatedScore): self
    {
        if ($this->aggregatedScores->contains($aggregatedScore)) {
            $this->aggregatedScores->removeElement($aggregatedScore);
            if ($aggregatedScore->getHotel() === $this) {
                $aggregatedScore->setHotel(null);
            }
        }

        return $this;
    }
}
