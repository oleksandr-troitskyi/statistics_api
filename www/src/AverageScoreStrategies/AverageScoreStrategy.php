<?php

namespace App\AverageScoreStrategies;

use App\Entity\DateRangeDTO;
use App\Entity\ScoreDTOCollection;
use App\Repository\AggregatedScoreRepository;

abstract class AverageScoreStrategy
{
    protected AggregatedScoreRepository $repository;

    public function __construct(AggregatedScoreRepository $repository)
    {
        $this->repository = $repository;
    }

    abstract public function getScores(int $hotelId, DateRangeDTO $dateRange): ScoreDTOCollection;
}
