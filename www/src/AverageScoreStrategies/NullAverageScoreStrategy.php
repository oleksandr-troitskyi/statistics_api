<?php

namespace App\AverageScoreStrategies;

use App\Entity\DateRangeDTO;
use App\Entity\ScoreDTOCollection;

class NullAverageScoreStrategy extends AverageScoreStrategy
{
    public function getScores(int $hotelId, DateRangeDTO $dateRange): ScoreDTOCollection
    {
        return new ScoreDTOCollection();
    }
}
