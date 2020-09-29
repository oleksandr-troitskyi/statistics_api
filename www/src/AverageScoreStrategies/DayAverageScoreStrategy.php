<?php

namespace App\AverageScoreStrategies;

use App\Entity\ScoreDTO;
use App\Entity\DateRangeDTO;
use App\Entity\ScoreDTOCollection;
use App\Entity\AggregatedScore;

class DayAverageScoreStrategy extends AverageScoreStrategy
{
    public function getScores(int $hotelId, DateRangeDTO $dateRange): ScoreDTOCollection
    {
        $averageScorePerDay = new ScoreDTOCollection();

        $scores = $this->repository->getScoresByDateRange($hotelId, $dateRange);

        /** @var AggregatedScore $score */
        foreach ($scores as $score) {
            $averageScorePerDay->addScore(
                new ScoreDTO($score->getReviews(), $score->getScore(), $score->getCreatedDate()->format('Y-m-d'))
            );
        }

        return $averageScorePerDay;
    }
}
