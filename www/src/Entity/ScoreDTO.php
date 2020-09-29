<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\SerializedName;

class ScoreDTO
{
    /**
     * @SerializedName("review-count")
     */
    private int $reviewCount;

    /**
     * @SerializedName("average-score")
     */
    private float $averageScore;

    /**
     * @SerializedName("date-group")
     */
    private string $dateGroup;

    /**
     * ScoreDTO constructor.
     *
     * @param int $reviewCount
     * @param float $averageScore
     * @param string $dateGroup
     */
    public function __construct(int $reviewCount, float $averageScore, string $dateGroup)
    {
        $this->reviewCount = $reviewCount;
        $this->averageScore = $averageScore;
        $this->dateGroup = $dateGroup;
    }

    /**
     * @return int
     */
    public function getReviewCount(): int
    {
        return $this->reviewCount;
    }

    /**
     * @return float
     */
    public function getAverageScore(): float
    {
        return $this->averageScore;
    }

    /**
     * @return string
     */
    public function getDateGroup(): string
    {
        return $this->dateGroup;
    }
}
