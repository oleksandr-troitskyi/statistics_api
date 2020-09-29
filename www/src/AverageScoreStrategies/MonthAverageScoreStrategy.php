<?php

namespace App\AverageScoreStrategies;

use App\Entity\ScoreDTO;
use App\Entity\DateRangeDTO;
use App\Entity\ScoreDTOCollection;
use App\Entity\AggregatedScore;

class MonthAverageScoreStrategy extends AverageScoreStrategy
{
    public function getScores(int $hotelId, DateRangeDTO $dateRange): ScoreDTOCollection
    {
        $scoresPerRange = [];
        $averageScorePerRange = new ScoreDTOCollection();

        $scores = $this->repository->getScoresByDateRange($hotelId, $dateRange);

        /** @var AggregatedScore $score */
        foreach ($scores as $score) {
            $scoresPerRange[$score->getCreatedDate()->format('Y-m')]['scores'][] = $score->getScore();
            $scoresPerRange[$score->getCreatedDate()->format('Y-m')]['reviews'][] = $score->getReviews();
        }

        foreach ($scoresPerRange as $range => $scorePerRange) {
            $averageScorePerRange->addScore(
                new ScoreDTO(
                    array_sum($scorePerRange['reviews']),
                    array_sum($scorePerRange['scores']) / count($scorePerRange['reviews']),
                    $range
                )
            );
        }

        return $averageScorePerRange;
    }
}
